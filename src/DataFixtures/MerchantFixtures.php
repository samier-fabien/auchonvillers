<?php

namespace App\DataFixtures;

use App\Entity\Merchant;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MerchantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $merchants = [
            1 => [
                "created_at" => new DateTime(),
                "content_fr" => "Corps du premier marchand en français",
                "content_en" => "First merchant's body in english",
            ],
            2 => [
                "created_at" => new DateTime(),
                "content_fr" => "Corps du deuxième marchand en français",
                "content_en" => "Second merchant's body in english",
            ]
        ];

        foreach ($merchants as $key => $value) {
            $merchant = new Merchant();
            $merchant->setMerCreatedAt($value["created_at"]);
            $merchant->setMerContentFr($value["content_fr"]);
            $merchant->setMerContentEn($value["content_en"]);
            $merchant->setUser($this->getReference("user_1"));

            $manager->persist($merchant);
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
