<?php

namespace App\Service;

class Regex
{
    public const IMAGE_SRC = '#src="(.+(?:\.jpg|\.jpeg|\.png))"#';
    
    public function findFirstImage(string $subject): string
    {
        $pattern = 
        $path = (preg_match(self::IMAGE_SRC, $subject, $match)) ? $match[1] : "/uploads/logo.png";

        return $path;
    }
}
