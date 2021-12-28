<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Events;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $events = [
            1 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "1",
            ],
            2 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "1",
            ],
        ];

        foreach ($events as $key => $value) {
            $event = new Events();
            $event->setEveCreatedAt($value["created_at"]);
            $event->setEveBegining($value["begining"]);
            $event->setEveEnd($value["end"]);
            $event->setEveContentFr($value["content_fr"]);
            $event->setEveContentEn($value["content_en"]);
            $event->setEveLocationOsm($value["location"]);
            $event->setUser($this->getReference("user_" . $value["user"]));

            $manager->persist($event);

            $this->addReference("event_" . $key, $event);
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
