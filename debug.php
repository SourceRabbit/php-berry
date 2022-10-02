<?php
require_once(__DIR__ . "/berry/utils.php"); // Include berry utils package

// Initialize a new Mailer
$mailer = new Mailer();

$htmlContent = '<h1>Email Title</h1>';
$htmlContent .= '<div>Dear customer, this is Elon Musk...</div>';

$subject = "Email subject...";
$fromEmail = "elonmusk@email.com";
$fromTitle = "Elon Musk";
$toEmail = "customer@customerdomain.com";

if (!$mailer->SendEmail($htmlContent, $subject, $fromEmail, $fromTitle, $toEmail))
{
    print "Unable to send the email!";
}
?>