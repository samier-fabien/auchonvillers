<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $date = new DateTime();
        
        $articles = [
            1 => [
                'content_fr' => 'Corps du premier article en français',
                'content_en' => 'First article\'s body in english',
                'order_of_appearance' => 1
            ],
            2 => [
                'content_fr' => 'Corps du deuxième article en français',
                'content_en' => 'Second article\'s body in english',
                'order_of_appearance' => 2
            ]
        ];

        $user = new User();
        // $user->setEmail("machin@truc.com");

        foreach ($articles as $key => $value) {
            $article = new Article();
            $article->setArtCreatedAt(new DateTime());
            $article->setArtContentFr($value['content_fr']);
            $article->setArtContentEn($value['content_en']);
            $article->setUser($this->getReference('mayor'));
            $article->setArtOrderOfAppearance($value["order_of_appearance"]);
            
            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
