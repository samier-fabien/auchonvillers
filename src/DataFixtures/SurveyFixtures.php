<?php

namespace App\DataFixtures;

use App\Entity\Survey;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SurveyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $surveys = [
            1 => [
                "action" => "3",
                "question_fr" => "Quelle fonctionnalité voudriez-vous voir ajoutée sur le site internet ?",
                "question_en" => "what feature would you like to be added to the website ?",
            ],
        ];

        foreach ($surveys as $key => $value) {
            $survey = new Survey();
            $survey->setAction($this->getReference("action_" . $value["action"]));
            $survey->setSurQuestionFr($value["question_fr"]);
            $survey->setSurQuestionEn($value["question_en"]);
            
            $manager->persist($survey);

            $this->addReference("survey_" . $key, $survey);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ActionFixtures::class,
        );
    }
}
