<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $art_created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $art_title_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $art_title_en;

    /**
     * @ORM\Column(type="text")
     */
    private $art_content_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $art_content_en;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtCreatedAt(): ?\DateTimeInterface
    {
        return $this->art_created_at;
    }

    public function setArtCreatedAt(\DateTimeInterface $art_created_at): self
    {
        $this->art_created_at = $art_created_at;

        return $this;
    }

    public function getArtTitleFr(): ?string
    {
        return $this->art_title_fr;
    }

    public function setArtTitleFr(string $art_title_fr): self
    {
        $this->art_title_fr = $art_title_fr;

        return $this;
    }

    public function getArtTitleEn(): ?string
    {
        return $this->art_title_en;
    }

    public function setArtTitleEn(?string $art_title_en): self
    {
        $this->art_title_en = $art_title_en;

        return $this;
    }

    public function getArtContentFr(): ?string
    {
        return $this->art_content_fr;
    }

    public function setArtContentFr(string $art_content_fr): self
    {
        $this->art_content_fr = $art_content_fr;

        return $this;
    }

    public function getArtContentEn(): ?string
    {
        return $this->art_content_en;
    }

    public function setArtContentEn(?string $art_content_en): self
    {
        $this->art_content_en = $art_content_en;

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
