<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Surveys;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SurveysFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $surveys = [
            1 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 11:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "question_fr" => "Quelle fonctionnalité voudriez-vous voir ajoutée sur le site internet ?",
                "question_en" => "what feature would you like to be added to the website ?",
                "user" => "1",
            ],
        ];

        foreach ($surveys as $key => $value) {
            $survey = new Surveys();
            $survey->setSurCreatedAt($value["created_at"]);
            $survey->setSurBegining($value["begining"]);
            $survey->setSurEnd($value["end"]);
            $survey->setSurContentFr($value["content_fr"]);
            $survey->setSurContentEn($value["content_en"]);
            $survey->setSurQuestionFr($value["question_fr"]);
            $survey->setSurQuestionEn($value["question_en"]);
            $survey->setUser($this->getReference("user_" . $value["user"]));

            $manager->persist($survey);

            $this->addReference("survey_" . $key, $survey);
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
