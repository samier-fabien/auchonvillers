<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChatFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $chats = [
            1 => [
                "user" => "user_2",
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 09:00:00"),
                "resolved" => false,
                "last_message" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 09:02:00")
            ],
            2 => [
                "user" => "user_3",
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 10:00:00"),
                "resolved" => false,
                "last_message" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-28-11 10:02:00")
            ],
        ];

        foreach ($chats as $key => $value) {
            $chat = new Chat();
            $chat->setUser($this->getReference($value["user"]));
            $chat->setChaCreatedAt($value["created_at"]);
            $chat->setChaResolved($value["resolved"]);
            $chat->setChaLastMessage($value["last_message"]);

            $manager->persist($chat);

            $this->addReference("chat_" . $key, $chat);
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
