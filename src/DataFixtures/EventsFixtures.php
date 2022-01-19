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
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-10 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "1",
            ],
            2 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-11 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "1",
            ],
            3 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-12 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "1",
            ],
            4 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-13 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "1",
            ],
            5 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-14 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "2",
            ],
            6 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-15 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "2",
            ],
            7 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-16 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "2",
            ],
            8 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-17 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "2",
            ],
            9 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-18 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "2",
            ],
            10 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-19 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "location" => "48.852969 2.349903",
                "user" => "2",
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
