<?php
require_once(__DIR__ . "/berry/utils.php"); // Include berry utils package

// Initialize an HTMLHelper
$htmlHelper = new HTMLHelper();

$string = "PHP-Berry framework for PHP backend applications";

// Convert $string to url friendly and print it
print $htmlHelper->URLFriendly($string);
?>