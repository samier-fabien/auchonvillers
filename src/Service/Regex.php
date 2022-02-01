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

    public function removeHtmlTags(string $text): string
    {
        $result = preg_replace(self::HTML_TAGS, "", $text);

        return $result;
    }

    public function textTruncate(string $text, int $maxCharacters): string
    {
        // Si nombre de caracteres > $characters on tronque à $characters - 3 on ajoute "..." et on retourne le resultat
        // Sinon on retourne $text
        if (strlen($text) > $maxCharacters) {
            return substr($text, 0, $maxCharacters - 3) . "...";
        } else {
            return $text;
        }
    }
}
