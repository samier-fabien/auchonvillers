<?php

namespace App\DataFixtures;

use App\Entity\Opinions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OpinionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $opinions = [
            1 => [
                "opinion" => "Je voudrais pouvoir créer des salons de discussion avec les autres abonnés.",
                "user" => "2",
                "survey" => "1"
            ],
            2 => [
                "opinion" => "Je voudrais pouvoir m'y connecter avec des lunettes 3d et pouvoir déambuler dans la ville comme si j'y étais.",
                "user" => "1",
                "survey" => "1"
            ],
        ];

        foreach ($opinions as $key => $value) {
            $opinion = new Opinions();
            $opinion->setOpiOpinion($value["opinion"]);
            $opinion->setUser($this->getReference("user_" . $value["user"]));
            $opinion->setSurvey($this->getReference("survey_" . $value["survey"]));

            $manager->persist($opinion);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            SurveysFixtures::class,
        );
    }
}
