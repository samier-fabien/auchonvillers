<?php

namespace App\DataFixtures;

use App\Entity\Vote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $votes = [
            1 => [
                "action" => "2",
                "question_fr" => "Est-ce que vous le trouvez utile ?",
                "question_en" => "Do you find it useful ?",
                "first_choice_fr" => "oui",
                "first_choice_en" => "yes",
                "second_choice_fr" => "non",
                "second_choice_en" => "no",
            ],
        ];

        foreach ($votes as $key => $value) {
            $vote = new Vote();
            $vote->setAction($this->getReference("action_" . $value["action"]));
            $vote->setVotQuestionFr($value["question_fr"]);
            $vote->setVotQuestionEn($value["question_en"]);
            $vote->setVotFirstChoiceFr($value["first_choice_fr"]);
            $vote->setVotFirstChoiceEn($value["first_choice_en"]);
            $vote->setVotSecondChoiceFr($value["second_choice_fr"]);
            $vote->setVotSecondeChoiceEn($value["second_choice_en"]);

            $manager->persist($vote);

            $this->addReference("vote_" . $key, $vote);
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
