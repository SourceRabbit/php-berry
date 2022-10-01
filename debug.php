<?php
require_once(__DIR__ . "/berry/utils.php"); // Include berry utils package

$crawlerDetector = new CrawlerDetector();
$crawlerName = $crawlerDetector->getCrawlerName();

if ($crawlerName != "")
{
    print "Crawler Detected: " . $crawlerName;
}
?>