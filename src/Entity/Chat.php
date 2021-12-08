<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
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
    private $cha_created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cha_resolved;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="chat", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cha_last_message;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChaCreatedAt(): ?\DateTimeInterface
    {
        return $this->cha_created_at;
    }

    public function setChaCreatedAt(\DateTimeInterface $cha_created_at): self
    {
        $this->cha_created_at = $cha_created_at;

        return $this;
    }

    public function getChaResolved(): ?bool
    {
        return $this->cha_resolved;
    }

    public function setChaResolved(bool $cha_resolved): self
    {
        $this->cha_resolved = $cha_resolved;

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

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

        return $this;
    }

    public function getChaLastMessage(): ?\DateTimeInterface
    {
        return $this->cha_last_message;
    }

    public function setChaLastMessage(\DateTimeInterface $cha_last_message): self
    {
        $this->cha_last_message = $cha_last_message;

        return $this;
    }
}
