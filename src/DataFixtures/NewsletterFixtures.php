<?php

namespace App\DataFixtures;

use App\Entity\Newsletter;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NewsletterFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $newsletters = [
            1 => [
                "created_at" => new DateTime(),
                "content_fr" => "Contenu de la première newsletter",
                "content_en" => "First newsletter's content"
            ],
            2 => [
                "created_at" => new DateTime(),
                "content_fr" => "Contenu de la première newsletter",
                "content_en" => "First newsletter's content"
            ]
        ];

        foreach ($newsletters as $key => $value) {
            $newsletter = new Newsletter();
            $newsletter->setNewCreatedAt($value["created_at"]);
            $newsletter->setNewContentFr($value["content_fr"]);
            $newsletter->setNewContentEn($value["content_en"]);
            $newsletter->setUser($this->getReference("user_1"));

            $manager->persist($newsletter);
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
