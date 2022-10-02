<?php

class Mailer
{

    private string $fCharset = "UTF-8";

    /**
     * Initialize a new Mailer object 
     * @param string $charset is the default charset to use for this Mailer. 
     * Leave empty for UTF-8
     */
    public function __construct(string $charset = "UTF-8")
    {
        $this->fCharset = $charset;
    }

    /**
     * Sends an HTML content email
     * @param string $htmlContent is the content of the email to send
     * @param string $subject is the subject of the email to send
     * @param string $senderAddress is the sender's email address
     * @param string $senderName is the sender's name
     * @param string $recepientEmail is the recipient's email address
     * @return bool true if email sent, otherwise false
     */
    public function SendEmail(string $htmlContent, string $subject, string $senderAddress, string $senderName, string $recepientEmail): bool
    {
        $senderName = strip_tags($senderName);
        $subject = strip_tags($subject);
        $header = "From: " . $senderName . " <" . $senderAddress . ">\r\n";
        $header .= "Content-type: text/html;charset=" . $this->fCharset . "\r\n";
        return mail($recepientEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $htmlContent, $header);
    }

}

?>