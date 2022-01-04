<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            1 => [
                'name_fr' => 'Mes démarches',
                'name_en' => 'My procedures',
                'description_fr' => 'Dans cet espace sont recensés des détails sur les démarches administratives : quand, pourquoi, comment, on vous dit tout ça pour chaque thème abordés.',
                'description_en' => 'In that space, we give you more details on administrative procedures : when, why, how, we tell you all about each adressed issue.',
                'order_of_appearance' => 10
            ],
            2 => [
                'name_fr' => 'Le tourisme',
                'name_en' => 'Tourism',
                'description_fr' => 'Dans cet espace sont recensés des détails sur le tourisme dans la zone d\'auchonvillers : quand, comment, on vous dit tout ça pour chaque thème abordés.',
                'description_en' => 'In that space, we give you more details on tourism around auchonvillers place : when, how, we tell you all about each adressed issue.',
                'order_of_appearance' => 10
            ],
        ];

        foreach ($categories as $key => $value) {
            $category = new Category();
            $category->setCatNameFr($value['name_fr'])
                ->setCatNameEn($value['name_en'])
                ->setCatDescriptionFr($value['description_fr'])
                ->setCatDescriptionEn($value['description_en'])
                ->setCatOrderOfAppearance($value['order_of_appearance'])
            ;

            $manager->persist($category);

            $this->addReference('category_' . $key, $category);
        }

        $manager->flush();
    }
}
