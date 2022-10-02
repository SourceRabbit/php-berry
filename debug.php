<?php
require_once(__DIR__ . "/berry/encryption.php"); // Include berry encryption package

$encryptionKey = "%2IR5wdW%Q7bLE*v+v4WpNfM*pkeM4sz"; // 32characters encryption key
$stringToEncrypt = "This is a simple text we need to encrypt";

print "Original String: " . $stringToEncrypt."<br>";

$encryptedString = AES256Encryption::encrypt($encryptionKey,$stringToEncrypt);
print "Encrypted String: " . $encryptedString."<br>";

$descryptedString = AES256Encryption::decrypt($encryptionKey, $encryptedString);
print "Decrypted String: " . $descryptedString."<br>";
?>