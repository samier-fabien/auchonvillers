<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Action;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $actions = [
            1 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-28-11 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-28-11 22:00:00"),
                "title_fr" => "Premier événement",
                "title_en" => "First event",
                "body_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "body_en" => "Little meeting with neighborhood for Fabien's birthday",
                "user" => "1",
            ],
            2 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 10:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 10:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-28-11 10:00:00"),
                "title_fr" => "Premier vote",
                "title_en" => "First vote",
                "body_fr" => "Nous voudrions savoir votre opinion sur le site internet.",
                "body_en" => "We would like to get your opinion about the website.",
                "user" => "1",
            ],
            3 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 10:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 10:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-28-11 10:00:00"),
                "title_fr" => "Première enquête",
                "title_en" => "First survey",
                "body_fr" => "Nous avons récemment réalisé le site web de la commune, c'est dans ce cadre que nous voudrions votre opinion.",
                "body_en" => "We have recently made the city's website, we would like to have your opinion about it.",
                "user" => "1",
            ],
            
        ];

        foreach ($actions as $key => $value) {
            $action = new Action();
            $action->setActCreatedAt($value["created_at"]);
            $action->setActBegining($value["begining"]);
            $action->setActEnd($value["end"]);
            $action->setActTitleFr($value["title_fr"]);
            $action->setActTitleEn($value["title_en"]);
            $action->setActBodyFr($value["body_fr"]);
            $action->setActBodyEn($value["body_en"]);
            $action->setUser($this->getReference("user_" . $value["user"]));

            $manager->persist($action);

            $this->addReference("action_" . $key, $action);
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
