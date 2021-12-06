<?php

namespace App\Entity;

use App\Repository\MerchantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MerchantRepository::class)
 */
class Merchant
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
    private $mer_created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mer_title_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mer_title_en;

    /**
     * @ORM\Column(type="text")
     */
    private $mer_description_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mer_description_en;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mer_location_osm;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="merchants")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMerCreatedAt(): ?\DateTimeInterface
    {
        return $this->mer_created_at;
    }

    public function setMerCreatedAt(\DateTimeInterface $mer_created_at): self
    {
        $this->mer_created_at = $mer_created_at;

        return $this;
    }

    public function getMerTitleFr(): ?string
    {
        return $this->mer_title_fr;
    }

    public function setMerTitleFr(string $mer_title_fr): self
    {
        $this->mer_title_fr = $mer_title_fr;

        return $this;
    }

    public function getMerTitleEn(): ?string
    {
        return $this->mer_title_en;
    }

    public function setMerTitleEn(?string $mer_title_en): self
    {
        $this->mer_title_en = $mer_title_en;

        return $this;
    }

    public function getMerDescriptionFr(): ?string
    {
        return $this->mer_description_fr;
    }

    public function setMerDescriptionFr(string $mer_description_fr): self
    {
        $this->mer_description_fr = $mer_description_fr;

        return $this;
    }

    public function getMerDescriptionEn(): ?string
    {
        return $this->mer_description_en;
    }

    public function setMerDescriptionEn(?string $mer_description_en): self
    {
        $this->mer_description_en = $mer_description_en;

        return $this;
    }

    public function getMerLocationOsm(): ?string
    {
        return $this->mer_location_osm;
    }

    public function setMerLocationOsm(?string $mer_location_osm): self
    {
        $this->mer_location_osm = $mer_location_osm;

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
