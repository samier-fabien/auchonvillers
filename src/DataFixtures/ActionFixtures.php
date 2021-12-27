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
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "user" => "1",
            ],
            2 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 10:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 10:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 10:00:00"),
                "content_fr" => "Nous voudrions savoir votre opinion sur le site internet.",
                "content_en" => "We would like to get your opinion about the website.",
                "user" => "1",
            ],
            3 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 11:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 11:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 11:00:00"),
                "content_fr" => "Nous avons récemment réalisé le site web de la commune, c'est dans ce cadre que nous voudrions votre opinion.",
                "content_en" => "We have recently made the city's website, we would like to have your opinion about it.",
                "user" => "1",
            ],
            4 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-29 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-29 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-29 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "user" => "1",
            ],
            5 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-29 10:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-29 10:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 10:00:00"),
                "content_fr" => "Nous voudrions savoir votre opinion sur le site internet.",
                "content_en" => "We would like to get your opinion about the website.",
                "user" => "1",
            ],
            6 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-29 11:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-29 11:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 11:00:00"),
                "content_fr" => "Nous avons récemment réalisé le site web de la commune, c'est dans ce cadre que nous voudrions votre opinion.",
                "content_en" => "We have recently made the city's website, we would like to have your opinion about it.",
                "user" => "1",
            ],
        ];

        foreach ($actions as $key => $value) {
            $action = new Action();
            $action->setActCreatedAt($value["created_at"]);
            $action->setActBegining($value["begining"]);
            $action->setActEnd($value["end"]);
            $action->setActContentFr($value["content_fr"]);
            $action->setActContentEn($value["content_en"]);
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
