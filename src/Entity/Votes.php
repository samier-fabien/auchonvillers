<?php

namespace App\Entity;

use App\Repository\VotesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VotesRepository::class)
 */
class Votes
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
    private $vot_created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $vot_begining;

    /**
     * @ORM\Column(type="datetime")
     */
    private $vot_end;

    /**
     * @ORM\Column(type="text")
     */
    private $vot_content_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $vot_content_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vot_question_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vot_question_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vot_first_choice_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vot_first_choice_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vot_second_choice_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vot_second_choice_en;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Ballots::class, mappedBy="vote", orphanRemoval=true)
     */
    private $ballots;

    public function __construct()
    {
        $this->ballots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVotCreatedAt(): ?\DateTimeInterface
    {
        return $this->vot_created_at;
    }

    public function setVotCreatedAt(\DateTimeInterface $vot_created_at): self
    {
        $this->vot_created_at = $vot_created_at;

        return $this;
    }

    public function getVotBegining(): ?\DateTimeInterface
    {
        return $this->vot_begining;
    }

    public function setVotBegining(\DateTimeInterface $vot_begining): self
    {
        $this->vot_begining = $vot_begining;

        return $this;
    }

    public function getVotEnd(): ?\DateTimeInterface
    {
        return $this->vot_end;
    }

    public function setVotEnd(\DateTimeInterface $vot_end): self
    {
        $this->vot_end = $vot_end;

        return $this;
    }

    public function getVotContentFr(): ?string
    {
        return $this->vot_content_fr;
    }

    public function setVotContentFr(string $vot_content_fr): self
    {
        $this->vot_content_fr = $vot_content_fr;

        return $this;
    }

    public function getVotContentEn(): ?string
    {
        return $this->vot_content_en;
    }

    public function setVotContentEn(?string $vot_content_en): self
    {
        $this->vot_content_en = $vot_content_en;

        return $this;
    }

    public function getVotQuestionFr(): ?string
    {
        return $this->vot_question_fr;
    }

    public function setVotQuestionFr(string $vot_question_fr): self
    {
        $this->vot_question_fr = $vot_question_fr;

        return $this;
    }

    public function getVotQuestionEn(): ?string
    {
        return $this->vot_question_en;
    }

    public function setVotQuestionEn(?string $vot_question_en): self
    {
        $this->vot_question_en = $vot_question_en;

        return $this;
    }

    public function getVotFirstChoiceFr(): ?string
    {
        return $this->vot_first_choice_fr;
    }

    public function setVotFirstChoiceFr(string $vot_first_choice_fr): self
    {
        $this->vot_first_choice_fr = $vot_first_choice_fr;

        return $this;
    }

    public function getVotFirstChoiceEn(): ?string
    {
        return $this->vot_first_choice_en;
    }

    public function setVotFirstChoiceEn(?string $vot_first_choice_en): self
    {
        $this->vot_first_choice_en = $vot_first_choice_en;

        return $this;
    }

    public function getVotSecondChoiceFr(): ?string
    {
        return $this->vot_second_choice_fr;
    }

    public function setVotSecondChoiceFr(string $vot_second_choice_fr): self
    {
        $this->vot_second_choice_fr = $vot_second_choice_fr;

        return $this;
    }

    public function getVotSecondChoiceEn(): ?string
    {
        return $this->vot_second_choice_en;
    }

    public function setVotSecondChoiceEn(?string $vot_second_choice_en): self
    {
        $this->vot_second_choice_en = $vot_second_choice_en;

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
     * @return Collection|Ballots[]
     */
    public function getBallots(): Collection
    {
        return $this->ballots;
    }

    public function addBallot(Ballots $ballot): self
    {
        if (!$this->ballots->contains($ballot)) {
            $this->ballots[] = $ballot;
            $ballot->setVote($this);
        }

        return $this;
    }

    public function removeBallot(Ballots $ballot): self
    {
        if ($this->ballots->removeElement($ballot)) {
            // set the owning side to null (unless already changed)
            if ($ballot->getVote() === $this) {
                $ballot->setVote(null);
            }
        }

        return $this;
    }
}
