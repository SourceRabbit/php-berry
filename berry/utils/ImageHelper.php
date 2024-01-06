<?php

class ImageHelper
{

    public function __construct()
    {
        
    }

    /**
     * Converts .jpg, .png and .gif images into .webp format
     * @param string $source the source image path to convert
     * @param int $quality ranges from 0 (worst quality, smaller file) to 100 (best quality, biggest file). 
     * @param bool $removeOld set to true if you want to delete the original file
     * @return string returns the .webp file path
     */
    public function ConvertImageToWebP(string $source, int $quality = 100, bool $removeOld = false): string
    {
        $dir = pathinfo($source, PATHINFO_DIRNAME);
        $name = pathinfo($source, PATHINFO_FILENAME);
        $destination = $dir . DIRECTORY_SEPARATOR . $name . '.webp';
        $info = getimagesize($source);
        $isAlpha = false;
        if ($info['mime'] == 'image/jpeg')
        {
            $image = imagecreatefromjpeg($source);
        }
        else if ($info['mime'] == 'image/gif')
        {
            $isAlpha = true;
            $image = imagecreatefromgif($source);
        }
        else if ($info['mime'] == 'image/png')
        {
            $isAlpha = true;
            $image = imagecreatefrompng($source);
        }
        else
        {
            return $source;
        }

        if ($isAlpha)
        {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }
        imagewebp($image, $destination, $quality);

        if ($removeOld)
        {
            unlink($source);
        }

        return $destination;
    }

   
}

?>