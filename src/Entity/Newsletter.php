<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsletterRepository::class)
 */
class Newsletter
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
    private $new_created_at;

    /**
     * @ORM\Column(type="text")
     */
    private $new_content_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $new_content_en;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="newsletters")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNewCreatedAt(): ?\DateTimeInterface
    {
        return $this->new_created_at;
    }

    public function setNewCreatedAt(\DateTimeInterface $new_created_at): self
    {
        $this->new_created_at = $new_created_at;

        return $this;
    }

    public function getNewContentFr(): ?string
    {
        return $this->new_content_fr;
    }

    public function setNewContentFr(string $new_content_fr): self
    {
        $this->new_content_fr = $new_content_fr;

        return $this;
    }

    public function getNewContentEn(): ?string
    {
        return $this->new_content_en;
    }

    public function setNewContentEn(?string $new_content_en): self
    {
        $this->new_content_en = $new_content_en;

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
}
