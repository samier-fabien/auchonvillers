<?php

namespace App\Service;

use App\Repository\CategoryRepository;

class Categories
{
    private $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
    }

    public function getList():array
    {
        return $this->categoryRepo->findAllOrderedByNumber();
    }
     
}