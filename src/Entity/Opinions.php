<?php

namespace App\Entity;

use App\Repository\OpinionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpinionsRepository::class)
 */
class Opinions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="opinions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Surveys::class, inversedBy="opinions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\Column(type="text")
     */
    private $opi_opinion;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSurvey(): ?Surveys
    {
        return $this->survey;
    }

    public function setSurvey(?Surveys $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    public function getOpiOpinion(): ?string
    {
        return $this->opi_opinion;
    }

    public function setOpiOpinion(string $opi_opinion): self
    {
        $this->opi_opinion = $opi_opinion;

        return $this;
    }
}
