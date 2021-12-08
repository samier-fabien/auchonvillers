<?php

namespace App\DataFixtures;

use App\Entity\Ballot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BallotFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $ballots = [
            1 => [
                "user" => "2",
                "vote" => "1",
                "response" => true,
            ],
            2 => [
                "user" => "3",
                "vote" => "1",
                "response" => true,
            ],
        ];

        foreach ($ballots as $key => $value) {
            $ballot = new Ballot();
            $ballot->setUser($this->getReference("user_" . $value["user"]));
            $ballot->setVote($this->getReference("vote_" . $value["vote"]));
            $ballot->setBalVote($value["response"]);

            $manager->persist($ballot);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            VoteFixtures::class,
        );
    }
}
