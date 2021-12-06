<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoteRepository::class)
 */
class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Action::class, inversedBy="vote", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\Column(type="text")
     */
    private $vot_question_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
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
    private $vot_seconde_choice_en;

    /**
     * @ORM\OneToMany(targetEntity=Ballot::class, mappedBy="vote", orphanRemoval=true)
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

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(Action $action): self
    {
        $this->action = $action;

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

    public function getVotSecondeChoiceEn(): ?string
    {
        return $this->vot_seconde_choice_en;
    }

    public function setVotSecondeChoiceEn(?string $vot_seconde_choice_en): self
    {
        $this->vot_seconde_choice_en = $vot_seconde_choice_en;

        return $this;
    }

    /**
     * @return Collection|Ballot[]
     */
    public function getBallots(): Collection
    {
        return $this->ballots;
    }

    public function addBallot(Ballot $ballot): self
    {
        if (!$this->ballots->contains($ballot)) {
            $this->ballots[] = $ballot;
            $ballot->setVote($this);
        }

        return $this;
    }

    public function removeBallot(Ballot $ballot): self
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
