<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Votes;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VotesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $votes = [
            1 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 09:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 18:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 22:00:00"),
                "content_fr" => "Petite réunion entre voisins pour fêter l'anniversaire de Fabien",
                "content_en" => "Little meeting with neighborhood for Fabien's birthday",
                "question_fr" => "Est-ce que vous le trouvez utile ?",
                "question_en" => "Do you find it useful ?",
                "first_choice_fr" => "oui",
                "first_choice_en" => "yes",
                "second_choice_fr" => "non",
                "second_choice_en" => "no",
                "user" => "1",
            ],
            2 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 10:00:00"),
                "begining" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 10:00:00"),
                "end" => Datetime::createFromFormat("Y-m-d H:i:s", "2022-11-28 10:00:00"),
                "content_fr" => "Nous voudrions savoir votre opinion sur le site internet.",
                "content_en" => "We would like to get your opinion about the website.",
                "question_fr" => "Est-ce que vous le trouvez utile ?",
                "question_en" => "Do you find it useful ?",
                "first_choice_fr" => "oui",
                "first_choice_en" => "yes",
                "second_choice_fr" => "non",
                "second_choice_en" => "no",
                "user" => "1",
            ],
        ];

        foreach ($votes as $key => $value) {
            $vote = new Votes();
            $vote->setVotCreatedAt($value["created_at"]);
            $vote->setVotBegining($value["begining"]);
            $vote->setVotEnd($value["end"]);
            $vote->setVotContentFr($value["content_fr"]);
            $vote->setVotContentEn($value["content_en"]);
            $vote->setVotQuestionFr($value["question_fr"]);
            $vote->setVotQuestionEn($value["question_en"]);
            $vote->setVotFirstChoiceFr($value["first_choice_fr"]);
            $vote->setVotFirstChoiceEn($value["first_choice_en"]);
            $vote->setVotSecondChoiceFr($value["second_choice_fr"]);
            $vote->setVotSecondChoiceEn($value["second_choice_en"]);
            $vote->setUser($this->getReference("user_" . $value["user"]));

            $manager->persist($vote);

            $this->addReference("vote_" . $key, $vote);
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
