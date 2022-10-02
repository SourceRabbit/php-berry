<?php

class Mailer
{

    public function __construct()
    {
        
    }

    /**
     * 
     * @param string $htmlContent
     * @param string $subject
     * @param string $fromEmail
     * @param string $fromTitle
     * @param string $toEmail
     * @return bool
     */
    public function SendEmail(string $htmlContent, string $subject, string $fromEmail, string $fromTitle, string $toEmail): bool
    {
        $fromTitle = strip_tags($fromTitle);
        $subject = strip_tags($subject);
        $header = "From: " . $fromTitle . " <" . $fromEmail . ">\r\n";
        $header .= "Content-type: text/html;charset=UTF-8\r\n";
        return mail($toEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $htmlContent, $header);
    }

}

?>