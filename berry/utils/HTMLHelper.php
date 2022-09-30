<?php

class HTMLHelper
{

    public function __construct()
    {
        
    }

    /**
     * Returns an array with HTML objects of the given tag type contained in 
     * the given html string.
     * @param string $tag the tag to search
     * @param string $html is the html string to search for tag objects
     * @return type array with html objects
     */
    public function GetAllTagsFromHTMLString(string $tag, string $html)
    {
        $imgs = array();
        preg_match_all('/<' . $tag . '[^>]+>/i', $html, $result);
        for ($i = 0; $i < sizeof($result); $i++)
        {
            $imgs[$i] = $result[0][$i];
        }
        return $imgs;
    }
}

?>