<?php

namespace App\Entity;

use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveyRepository::class)
 */
class Survey
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Action::class, inversedBy="survey", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sur_question_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sur_question_en;

    /**
     * @ORM\OneToMany(targetEntity=Opinion::class, mappedBy="survey", orphanRemoval=true)
     */
    private $opinions;

    public function __construct()
    {
        $this->opinions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSurQuestionFr(): ?string
    {
        return $this->sur_question_fr;
    }

    public function setSurQuestionFr(string $sur_question_fr): self
    {
        $this->sur_question_fr = $sur_question_fr;

        return $this;
    }

    public function getSurQuestionEn(): ?string
    {
        return $this->sur_question_en;
    }

    public function setSurQuestionEn(?string $sur_question_en): self
    {
        $this->sur_question_en = $sur_question_en;

        return $this;
    }

    /**
     * @return Collection|Opinion[]
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setSurvey($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getSurvey() === $this) {
                $opinion->setSurvey(null);
            }
        }

        return $this;
    }
}
