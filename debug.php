<?php
require_once(__DIR__ . "/berry/email.php"); // Include berry email package

$htmlContent = '<h1>Email Title</h1>';
$htmlContent .= '<div>Dear customer, this is Elon Musk...</div>';

$email = new EMail();
$email->setSenderAddress("elonmusk@email.com");
$email->setSenderName("Elon Musk");
$email->setRecipientAddress("customer@customerdomain.com");
$email->setSubject("This is a test");
$email->setHTMLContent($htmlContent);

if ($email->Send())
{
    print "Email sent!";
}
else
{
    print "Unable to send the email!";
}
?>