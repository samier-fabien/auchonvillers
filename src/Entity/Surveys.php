<?php

namespace App\Entity;

use App\Repository\SurveysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveysRepository::class)
 */
class Surveys
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
    private $sur_created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sur_begining;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sur_end;

    /**
     * @ORM\Column(type="text")
     */
    private $sur_content_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sur_content_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sur_question_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sur_question_en;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="surveys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Opinions::class, mappedBy="survey", orphanRemoval=true)
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

    public function getSurCreatedAt(): ?\DateTimeInterface
    {
        return $this->sur_created_at;
    }

    public function setSurCreatedAt(\DateTimeInterface $sur_created_at): self
    {
        $this->sur_created_at = $sur_created_at;

        return $this;
    }

    public function getSurBegining(): ?\DateTimeInterface
    {
        return $this->sur_begining;
    }

    public function setSurBegining(\DateTimeInterface $sur_begining): self
    {
        $this->sur_begining = $sur_begining;

        return $this;
    }

    public function getSurEnd(): ?\DateTimeInterface
    {
        return $this->sur_end;
    }

    public function setSurEnd(\DateTimeInterface $sur_end): self
    {
        $this->sur_end = $sur_end;

        return $this;
    }

    public function getSurContentFr(): ?string
    {
        return $this->sur_content_fr;
    }

    public function setSurContentFr(string $sur_content_fr): self
    {
        $this->sur_content_fr = $sur_content_fr;

        return $this;
    }

    public function getSurContentEn(): ?string
    {
        return $this->sur_content_en;
    }

    public function setSurContentEn(?string $sur_content_en): self
    {
        $this->sur_content_en = $sur_content_en;

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
     * @return Collection|Opinions[]
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinions $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setSurvey($this);
        }

        return $this;
    }

    public function removeOpinion(Opinions $opinion): self
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
