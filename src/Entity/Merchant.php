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
     * @ORM\Column(type="text")
     */
    private $mer_content_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mer_content_en;

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

    public function getMerContentFr(): ?string
    {
        return $this->mer_content_fr;
    }

    public function setMerContentFr(string $mer_content_fr): self
    {
        $this->mer_content_fr = $mer_content_fr;

        return $this;
    }

    public function getMerContentEn(): ?string
    {
        return $this->mer_content_en;
    }

    public function setMerContentEn(?string $mer_content_en): self
    {
        $this->mer_content_en = $mer_content_en;

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
