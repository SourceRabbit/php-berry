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
     * @return string array with html objects
     */
    public function GetHTMLTags(string $tag, string $html): array
    {
        $images = array();
        $regexResult = array();
        preg_match_all('/<' . $tag . '[^>]+>/i', $html, $regexResult);
        for ($i = 0; $i < sizeof($regexResult[0]); $i++)
        {
            $images[$i] = $regexResult[0][$i];
        }
        return $images;
    }

    /**
     * Converts a string to a URL friendy format
     * @param string $string the string to convert to url friendly
     * @return string 
     */
    public function URLFriendly(string $string): string
    {
        // Remove unwanted characters
        $string = strtolower($string);
        $unwantedChars = ['.', '!', '(', ')', '\\', '/', '"', ' '];
        $string = str_replace($unwantedChars, ['', '', '', '', '', '', '', '-'], $string);
        $string = preg_replace("`\[.*\]`U", "", $string);
        $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $string);
        $string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string);
        $string = preg_replace('~[^a-z0-9\-]+~', '', $string);
        $string = preg_replace('~-+~', '-', $string);
        return trim($string, '-');
    }
}

?>