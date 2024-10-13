<?php

class SMTPEMail
{

    const CRLF = "\r\n";
    const TLS = 'tcp';
    const SSL = 'ssl';
    const OK = 250;

    protected $fSMTPHost;
    protected $fMachineHostName;
    protected $fSMTPPort;
    protected $fSocket;
    protected $fSMTPUsername;
    protected $fSMTPPassword;
    protected $fSMTPConnectionTimeout;
    protected $fSMTPResponseTimeout;
    protected $fSubject;
    protected $fTo = array();
    protected $fCC = array();
    protected $fBCC = array();
    protected $fFrom = array();
    protected $fReplyTo = array();
    protected $fAttachments = array();
    protected $fProtocol = null;
    protected $fTextMessage = null;
    protected $fHTMLMessage = null;
    protected $fIsHTML = false;
    protected $fIsTLS = false;
    protected $fLogs = array();
    protected $fCharset = 'utf-8';
    protected $fHeaders = array();

    /**
     * Set server name, port and timeout values
     *
     * @param string $server
     * @param int $port
     * @param int $connectionTimeout
     * @param int $responseTimeout
     * @param string|null $hostname
     */
    public function __construct($server, $port = 25, $connectionTimeout = 30, $responseTimeout = 8, $hostname = null)
    {
        $this->fSMTPPort = $port;
        $this->fSMTPHost = $server;
        $this->fSMTPConnectionTimeout = $connectionTimeout;
        $this->fSMTPResponseTimeout = $responseTimeout;
        $this->fMachineHostName = empty($hostname) ? gethostname() : $hostname;
        $this->setHeader('X-Mailer', 'PHP/' . phpversion());
        $this->setHeader('MIME-Version', '1.0');
    }

    /**
     * @param string $key
     * @param mixed|null $value
     * @return Email
     */
    public function setHeader($key, $value = null)
    {
        $this->fHeaders[$key] = $value;
        return $this;
    }

    /**
     * Add to recipient email address
     *
     * @param string $address
     * @param string|null $name
     * @return Email
     */
    public function addTo($address, $name = null)
    {
        $this->fTo[] = array($address, $name);
        return $this;
    }

    /**
     * Add carbon copy email address
     *
     * @param string $address
     * @param string|null $name
     * @return Email
     */
    public function addCc($address, $name = null)
    {
        $this->fCC[] = array($address, $name);
        return $this;
    }

    /**
     * Add blind carbon copy email address
     *
     * @param string $address
     * @param string|null $name
     * @return Email
     */
    public function addBcc($address, $name = null)
    {
        $this->fBCC[] = array($address, $name);
        return $this;
    }

    /**
     * Add email reply to address
     *
     * @param string $address
     * @param string|null $name
     * @return Email
     */
    public function addReplyTo($address, $name = null)
    {
        $this->fReplyTo[] = array($address, $name);
        return $this;
    }

    /**
     * Add file attachment
     *
     * @param string $attachment
     * @return Email
     */
    public function addAttachment($attachment)
    {
        if (file_exists($attachment))
        {
            $this->fAttachments[] = $attachment;
        }
        return $this;
    }

    /**
     * Set SMTP Login authentication
     *
     * @param string $username
     * @param string $password
     * @return Email
     */
    public function setLogin($username, $password)
    {
        $this->fSMTPUsername = $username;
        $this->fSMTPPassword = $password;
        return $this;
    }

    /**
     * Get message character set
     *
     * @param string $charset
     * @return Email
     */
    public function setCharset($charset)
    {
        $this->fCharset = $charset;
        return $this;
    }

    /**
     * Set SMTP Server protocol
     * -- default value is null (no secure protocol)
     *
     * @param string $protocol
     * @return Email
     */
    public function setProtocol($protocol = null)
    {
        if ($protocol === self::TLS)
        {
            $this->fIsTLS = true;
        }

        $this->fProtocol = $protocol;
        return $this;
    }

    /**
     * Set from email address and/or name
     *
     * @param string $address
     * @param string|null $name
     * @return Email
     */
    public function setFrom($address, $name = null)
    {
        $this->fFrom = array($address, $name);
        return $this;
    }

    /**
     * Set email subject string
     *
     * @param string $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->fSubject = $subject;
        return $this;
    }

    /**
     * Set plain text message body
     *
     * @param string $message
     * @return Email
     */
    public function setTextMessage($message)
    {
        $this->fTextMessage = $message;
        return $this;
    }

    /**
     * Set html message body
     *
     * @param string $message
     * @return Email
     */
    public function setHtmlMessage($message)
    {
        $this->fHTMLMessage = $message;
        return $this;
    }

    /**
     * Get log array
     * -- contains commands and responses from SMTP server
     *
     * @return array
     */
    public function getLogs()
    {
        return $this->fLogs;
    }

    /**
     * Send email to recipient via mail server
     *
     * @return bool
     */
    public function Send()
    {
        $message = null;
        $this->fSocket = fsockopen($this->getServer(), $this->fSMTPPort, $errorNumber, $errorMessage, $this->fSMTPConnectionTimeout);

        if (empty($this->fSocket))
        {
            return false;
        }

        $this->fLogs['CONNECTION'] = $this->getResponse();
        $this->fLogs['HELLO'][1] = $this->sendCommand('EHLO ' . $this->fMachineHostName);

        if ($this->fIsTLS)
        {
            $this->fLogs['STARTTLS'] = $this->sendCommand('STARTTLS');
            stream_socket_enable_crypto($this->fSocket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->fLogs['HELLO'][2] = $this->sendCommand('EHLO ' . $this->fMachineHostName);
        }

        $this->fLogs['AUTH'] = $this->sendCommand('AUTH LOGIN');
        $this->fLogs['USERNAME'] = $this->sendCommand(base64_encode($this->fSMTPUsername));
        $this->fLogs['PASSWORD'] = $this->sendCommand(base64_encode($this->fSMTPPassword));
        $this->fLogs['MAIL_FROM'] = $this->sendCommand('MAIL FROM: <' . $this->fFrom[0] . '>');

        $recipients = array_merge($this->fTo, $this->fCC, $this->fBCC);
        foreach ($recipients as $address)
        {
            $this->fLogs['RECIPIENTS'][] = $this->sendCommand('RCPT TO: <' . $address[0] . '>');
        }

        $this->setHeader('Date', date('r'));
        $this->setHeader('Subject', $this->fSubject);
        $this->setHeader('From', $this->formatAddress($this->fFrom));
        $this->setHeader('Return-Path', $this->formatAddress($this->fFrom));
        $this->setHeader('To', $this->formatAddressList($this->fTo));

        if (!empty($this->fReplyTo))
        {
            $this->setHeader('Reply-To', $this->formatAddressList($this->fReplyTo));
        }

        if (!empty($this->fCC))
        {
            $this->setHeader('Cc', $this->formatAddressList($this->fCC));
        }

        if (!empty($this->fBCC))
        {
            $this->setHeader('Bcc', $this->formatAddressList($this->fBCC));
        }

        $boundary = md5(uniqid(microtime(true), true));
        $this->setHeader('Content-Type', 'multipart/mixed; boundary="mixed-' . $boundary . '"');

        if (!empty($this->fAttachments))
        {
            $this->fHeaders['Content-Type'] = 'multipart/mixed; boundary="mixed-' . $boundary . '"';
            $message .= '--mixed-' . $boundary . self::CRLF;
            $message .= 'Content-Type: multipart/alternative; boundary="alt-' . $boundary . '"' . self::CRLF . self::CRLF;
        }
        else
        {
            $this->fHeaders['Content-Type'] = 'multipart/alternative; boundary="alt-' . $boundary . '"';
        }

        if (!empty($this->fTextMessage))
        {
            $message .= '--alt-' . $boundary . self::CRLF;
            $message .= 'Content-Type: text/plain; charset=' . $this->fCharset . self::CRLF;
            $message .= 'Content-Transfer-Encoding: base64' . self::CRLF . self::CRLF;
            $message .= chunk_split(base64_encode($this->fTextMessage)) . self::CRLF;
        }

        if (!empty($this->fHTMLMessage))
        {
            $message .= '--alt-' . $boundary . self::CRLF;
            $message .= 'Content-Type: text/html; charset=' . $this->fCharset . self::CRLF;
            $message .= 'Content-Transfer-Encoding: base64' . self::CRLF . self::CRLF;
            $message .= chunk_split(base64_encode($this->fHTMLMessage)) . self::CRLF;
        }

        $message .= '--alt-' . $boundary . '--' . self::CRLF . self::CRLF;

        if (!empty($this->fAttachments))
        {
            foreach ($this->fAttachments as $attachment)
            {
                $filename = pathinfo($attachment, PATHINFO_BASENAME);
                $contents = file_get_contents($attachment);
                $type = mime_content_type($attachment);
                if (!$type)
                {
                    $type = 'application/octet-stream';
                }

                $message .= '--mixed-' . $boundary . self::CRLF;
                $message .= 'Content-Type: ' . $type . '; name="' . $filename . '"' . self::CRLF;
                $message .= 'Content-Disposition: attachment; filename="' . $filename . '"' . self::CRLF;
                $message .= 'Content-Transfer-Encoding: base64' . self::CRLF . self::CRLF;
                $message .= chunk_split(base64_encode($contents)) . self::CRLF;
            }

            $message .= '--mixed-' . $boundary . '--';
        }

        $headers = '';
        foreach ($this->fHeaders as $k => $v)
        {
            $headers .= $k . ': ' . $v . self::CRLF;
        }

        $this->fLogs['MESSAGE'] = $message;
        $this->fLogs['HEADERS'] = $headers;
        $this->fLogs['DATA'][1] = $this->sendCommand('DATA');
        $this->fLogs['DATA'][2] = $this->sendCommand($headers . self::CRLF . $message . self::CRLF . '.');
        $this->fLogs['QUIT'] = $this->sendCommand('QUIT');
        fclose($this->fSocket);

        return substr($this->fLogs['DATA'][2], 0, 3) == self::OK;
    }

    /**
     * Get server url
     * -- if set SMTP protocol then prepend it to server
     *
     * @return string
     */
    protected function getServer()
    {
        return ($this->fProtocol) ? $this->fProtocol . '://' . $this->fSMTPHost : $this->fSMTPHost;
    }

    /**
     * Get Mail Server response
     * @return string
     */
    protected function getResponse()
    {
        $response = '';
        stream_set_timeout($this->fSocket, $this->fSMTPResponseTimeout);
        while (($line = fgets($this->fSocket, 515)) !== false)
        {
            $response .= trim($line) . "\n";
            if (substr($line, 3, 1) == ' ')
            {
                break;
            }
        }

        return trim($response);
    }

    /**
     * Send command to mail server
     *
     * @param string $command
     * @return string
     */
    protected function sendCommand($command)
    {
        fputs($this->fSocket, $command . self::CRLF);
        return $this->getResponse();
    }

    /**
     * Format email address (with name)
     *
     * @param array $address
     * @return string
     */
    protected function formatAddress($address)
    {
        return (empty($address[1])) ? $address[0] : '"' . addslashes($address[1]) . '" <' . $address[0] . '>';
    }

    /**
     * Format email address to list
     *
     * @param array $addresses
     * @return string
     */
    protected function formatAddressList(array $addresses)
    {
        $data = array();
        foreach ($addresses as $address)
        {
            $data[] = $this->formatAddress($address);
        }

        return implode(', ', $data);
    }
}

?>