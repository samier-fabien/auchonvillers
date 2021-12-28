<?php

namespace App\DataFixtures;

use App\Entity\Attends;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AttendsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $attends = [
            1 => [
                "user" => "1",
                "event" => "1"
            ],
            2 => [
                "user" => "1",
                "event" => "2"
            ],
        ];

        foreach ($attends as $key => $value) {
            $attends = new Attends();
            $attends->setUser($this->getReference("user_" . $value["user"]));
            $attends->setEvent($this->getReference("event_" . $value["event"]));

            $manager->persist($attends);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            EventsFixtures::class,
        );
    }
}
