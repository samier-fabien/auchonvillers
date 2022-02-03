<?php

namespace App\Service;

use Liip\ImagineBundle\Service\FilterService;

/**
 * @see https://packagist.org/packages/liip/imagine-bundle
 */
class Imagine
{
    private $imagine;

    public function __construct(FilterService $imagine) {
        $this->imagine = $imagine;
    }


    public function toSquareFourHundreds(string $imagePath): string
    {
        return $this->imagine->getUrlOfFilteredImageWithRuntimeFilters($imagePath, 'my_thumb');
    }

    public function toSquareTwoHundreds(string $imagePath): string
    {
        return $this->imagine->getUrlOfFilteredImageWithRuntimeFilters($imagePath, 'my_little_thumb');
    }

    // Exemple d'utilisation avec runtimeConfig
    // public function toSquareFourHundreds(string $imagePath): string
    // {
    //     $runtimeConfig = array(
    //         "thumbnail" => array(
    //             "size" => array(400, 400)
    //         )
    //     );

    //     return $this->imagine->getUrlOfFilteredImageWithRuntimeFilters($imagePath, 'my_thumb');
    // }
}