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
                'title_fr' => 'Le premier article',
                'title_en' => 'The first article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 10,
                'category' => 1
            ],
            2 => [
                'title_fr' => 'Un deuxième article',
                'title_en' => 'A second article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 20,
                'category' => 1
            ],
            3 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 30,
                'category' => 1
            ],
            4 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 40,
                'category' => 1
            ],
            5 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 50,
                'category' => 1
            ],
            6 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 60,
                'category' => 1
            ],
            7 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 70,
                'category' => 1
            ],
            8 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 80,
                'category' => 1
            ],
            9 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 90,
                'category' => 1
            ],
            10 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 100,
                'category' => 2
            ],
            11 => [
                'title_fr' => 'Le premier article',
                'title_en' => 'The first article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 10,
                'category' => 2
            ],
            12 => [
                'title_fr' => 'Un deuxième article',
                'title_en' => 'A second article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 20,
                'category' => 2
            ],
            13 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 30,
                'category' => 2
            ],
            14 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 40,
                'category' => 2
            ],
            15 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 50,
                'category' => 2
            ],
            16 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 60,
                'category' => 2
            ],
            17 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 70,
                'category' => 2
            ],
            18 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 80,
                'category' => 2
            ],
            19 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 90,
                'category' => 2
            ],
            20 => [
                'title_fr' => 'Un article',
                'title_en' => 'An article',
                'content_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'content_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum voluptatum atque ratione suscipit, ipsa rerum voluptatem. Tenetur tempore, sequi voluptate doloribus debitis cum consequatur quibusdam aperiam totam dolores aspernatur illo.',
                'order_of_appearance' => 100,
                'category' => 2
            ],
        ];

        foreach ($articles as $key => $value) {
            $article = new Article();
            $article->setArtCreatedAt(new DateTime())
                ->setArtTitleFr($value['title_fr'])
                ->setArtTitleEn($value['title_en'])
                ->setArtContentFr($value['content_fr'])
                ->setArtContentEn($value['content_en'])
                ->setArtOrderOfAppearance($value["order_of_appearance"])
                ->setUser($this->getReference('mayor'))
                ->setCategory($this->getReference('category_'. $value['category']))
            ;

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
