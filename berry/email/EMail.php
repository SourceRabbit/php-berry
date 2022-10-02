<?php

class EMail
{

    private string $fCharset;
    private string $fSenderEmailAddress;
    private string $fSenderName = "";
    private string $fRecipientEmailAddress;
    private string $fSubject = "";
    private string $fHTMLContent = "";

    /**
     * Initialize a new email object 
     * @param string $charset is the default charset to use for this email. 
     * Leave empty for UTF-8
     */
    public function __construct(string $charset = "UTF-8")
    {
        $this->fCharset = $charset;
    }

    /**
     * Returns the charset of this email
     * @return string the charset of this email
     */
    public function getCharset(): string
    {
        return $this->fCharset;
    }

    /**
     * Sets the sender's email address
     * @param string $senderEmail the email address of the sender
     */
    public function setSenderAddress(string $senderEmail): void
    {
        $this->fSenderEmailAddress = $senderEmail;
    }

    /**
     * Return's the sender's email address
     * @return string the sender's email address
     */
    public function getSenderAddress(): string
    {
        return $this->fSenderEmailAddress;
    }

    /**
     * Sets the sender's name
     * @param string the email address of the sender
     */
    public function setSenderName(string $senderName): void
    {
        $this->fSenderName = strip_tags($senderName);
    }

    /**
     * Return's the sender's name
     * @return string the name of the sender
     */
    public function getSenderName(): string
    {
        return $this->fSenderName;
    }

    /**
     * Sets the recipient's email address
     * @param string the email address of the recipient
     */
    public function setRecipientAddress(string $recipientAddress): void
    {
        $this->fRecipientEmailAddress = $recipientAddress;
    }

    /**
     * Return's the recipient's email address
     * @return string the recipient's email address
     */
    public function getRecipientEmailAddress(): string
    {
        return $this->fRecipientEmailAddress;
    }

    /**
     * Sets the subject of this email
     * @param string $subject
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->fSubject = strip_tags($subject);
    }

    /**
     * Return's the subject of this email
     * @return string the subject of this email
     */
    public function getSubject(): string
    {
        return $this->fSubject;
    }

    /**
     * Sets the HTML content of this email
     * @param string $htmlContent the content of this email
     * @return void
     */
    public function setHTMLContent(string $htmlContent): void
    {
        $this->fHTMLContent = $htmlContent;
    }

    /**
     * Return's the content of this email
     * @return string the html content of this email
     */
    public function getHTMLContent(): string
    {
        return $this->fHTMLContent;
    }

    /**
     * Tries to send this email.
     * @return bool true if email sent, otherwise false
     */
    public function Send(): bool
    {
        $header = "From: " . $this->fSenderName . " <" . $this->fSenderEmailAddress . ">\r\n";
        $header .= "Content-type: text/html;charset=" . $this->fCharset . "\r\n";
        return mail($this->fRecipientEmailAddress, '=?UTF-8?B?' . base64_encode($this->fSubject) . '?=', $this->fHTMLContent, $header);
    }

}

?>