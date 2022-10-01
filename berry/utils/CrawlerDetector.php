<?php

class CrawlerDetector
{

    public function __construct()
    {
        
    }

    /**
     * If the current client is a crawler then this method returns the crawler's name. 
     * Otherwise it returns (String empty) ""
     * 
     * @return string returns the crawlers name or (String empty) "" 
     */
    public function getCrawlerName(): string
    {
        $USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $pattern = '/bot|wget|crawl|slurp|spider|mediapartners|facebook/i';
        return preg_match($pattern, $USER_AGENT) ? $USER_AGENT : "";
    }

}

?>