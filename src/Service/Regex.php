<?php

namespace App\Service;

class Regex
{
    public const IMAGE_SRC = '#src="(.+(?:\.jpg|\.jpeg|\.png))"#';
    public const HTML_TAGS = "#<[^>]*>#";
    
    public function findFirstImage(string $text): string
    {
        $pattern = 
        $path = (preg_match(self::IMAGE_SRC, $text, $match)) ? $match[1] : "/uploads/logo.png";

        return $path;
    }

    public function removeHtmlTags(string $text)
    {
        $result = preg_replace(self::HTML_TAGS, "", $text);

        return $result;
    }
}
