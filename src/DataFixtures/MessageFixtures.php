<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Message;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $messages = [
            1 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 09:00:00"),
                "content" => "Bonjour, comment allez-vous ?",
                "user" => "2",
                "chat" => "1"
            ],
            2 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 09:01:00"),
                "content" => "Très bien, merci. En quoi puis-je vous aider ?",
                "user" => "1",
                "chat" => "1"
            ],
            3 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 09:02:00"),
                "content" => "Et bien j'ai besoin de renseignements...",
                "user" => "2",
                "chat" => "1"
            ],
            4 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 10:00:00"),
                "content" => "Bonjour !",
                "user" => "3",
                "chat" => "2"
            ],
            5 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 10:01:00"),
                "content" => "Bonjour, en quoi puis-je vous aider ?",
                "user" => "1",
                "chat" => "2"
            ],
            6 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 10:02:00"),
                "content" => "Je voulais savoir ...",
                "user" => "3",
                "chat" => "2"
            ],
        ];

        foreach ($messages as $key => $value) {
            $message = new Message();
            $message->setMesCreatedAt($value["created_at"]);
            $message->setMesContent($value["content"]);
            $message->setUser($this->getReference("user_" . $value["user"]));
            $message->setChat($this->getReference("chat_" . $value["chat"]));

            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ChatFixtures::class,
        );
    }
}
