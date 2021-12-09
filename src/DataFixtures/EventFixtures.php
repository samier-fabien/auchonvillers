<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $events = [
            1 => [
                "location" => "48.852969 2.349903",
                "action" => "1",
            ],
            2 => [
                "location" => "48.852969 2.349903",
                "action" => "4",
            ],
        ];

        foreach ($events as $key => $value) {
            $event = new Event();
            $event->setEveLocationOsm($value["location"]);
            $event->setAction($this->getReference('action_' . $value["action"]));

            $manager->persist($event);

            $this->addReference("event_" . $key, $event);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ActionFixtures::class,
        );
    }
}
