<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $mes_created_at;

    /**
     * @ORM\Column(type="text")
     */
    private $mes_content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Chat::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMesCreatedAt(): ?\DateTimeInterface
    {
        return $this->mes_created_at;
    }

    public function setMesCreatedAt(\DateTimeInterface $mes_created_at): self
    {
        $this->mes_created_at = $mes_created_at;

        return $this;
    }

    public function getMesContent(): ?string
    {
        return $this->mes_content;
    }

    public function setMesContent(string $mes_content): self
    {
        $this->mes_content = $mes_content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }
}
