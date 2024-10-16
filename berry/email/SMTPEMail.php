<?php

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SMTPEMail
{

    private PHPMailer $fPHPMailer;

    /**
     * Initialize a new smtpemail object 
     * @param string $smtpHostName
     * @param int $smtpPort
     * @param string $smtpUsername
     * @param string $smtpPassword
     */
    public function __construct(string $smtpHostName, int $smtpPort, string $smtpUsername, string $smtpPassword, string $encryption = "")
    {
        $this->fPHPMailer = new PHPMailer(true);
        $this->fPHPMailer->isSMTP();
        $this->fPHPMailer->CharSet = 'UTF-8';
        $this->fPHPMailer->Host = $smtpHostName;
        $this->fPHPMailer->SMTPAuth = true;
        $this->fPHPMailer->Username = $smtpUsername;
        $this->fPHPMailer->Password = $smtpPassword;
        $this->fPHPMailer->SMTPSecure = $encryption;
        $this->fPHPMailer->Port = $smtpPort;
        $this->fPHPMailer->isHTML = true;
    }

    /**
     * Sets senders information
     * @param string $emailAddress
     * @param string $senderName
     * @return void
     */
    public function setFrom(string $emailAddress, string $senderName): void
    {
        $this->fPHPMailer->setFrom($emailAddress, $senderName);
    }

    /**
     * Add a recipient to your email
     * @param string $recipientsEmailAddress
     * @return void
     */
    public function addTo(string $recipientsEmailAddress): void
    {
        $this->fPHPMailer->addAddress($recipientsEmailAddress);
    }

    /**
     * Set subject
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->fPHPMailer->Subject = $subject;
    }

    /**
     * Set HTML Message
     * @param string $html
     */
    public function setHtmlMessage(string $html)
    {
        $this->fPHPMailer->isHTML(true);
        $this->fPHPMailer->Body = $html;
        $this->fPHPMailer->AltBody = strip_tags($this->fPHPMailer->Body);
    }

    /**
     * Send the email
     * @return bool
     */
    public function Send(): bool
    {
        return $this->fPHPMailer->send();
    }
}

?>