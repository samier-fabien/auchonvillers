<?php

namespace App\DataFixtures;

use App\Entity\Attend;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AttendFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $attends = [
            1 => [
                "user" => "1",
                "event" => "1"
            ],
        ];

        foreach ($attends as $key => $value) {
            $attend = new Attend();
            $attend->setUser($this->getReference("user_" . $value["user"]));
            $attend->setEvent($this->getReference("event_" . $value["event"]));

            $manager->persist($attend);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            EventFixtures::class,
        );
    }
}
