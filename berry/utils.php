<?php
require_once(__DIR__ . "/utils/CrawlerDetector.php");

$crawlerDetector = new CrawlerDetector();
$crawlerName = $crawlerDetector->getCrawlerName();

if ($crawlerName != "")
{
    print "Cralwer Detected: " . $crawlerDetector;
}
?>