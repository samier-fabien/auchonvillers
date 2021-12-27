<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionRepository::class)
 */
class Action
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
    private $act_created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_begining;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_end;

    /**
     * @ORM\Column(type="text")
     */
    private $act_content_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $act_content_en;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="actions")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Event::class, mappedBy="action", cascade={"persist", "remove"})
     */
    private $event;

    /**
     * @ORM\OneToOne(targetEntity=Vote::class, mappedBy="action", cascade={"persist", "remove"})
     */
    private $vote;

    /**
     * @ORM\OneToOne(targetEntity=Survey::class, mappedBy="action", cascade={"persist", "remove"})
     */
    private $survey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActCreatedAt(): ?\DateTimeInterface
    {
        return $this->act_created_at;
    }

    public function setActCreatedAt(\DateTimeInterface $act_created_at): self
    {
        $this->act_created_at = $act_created_at;

        return $this;
    }

    public function getActBegining(): ?\DateTimeInterface
    {
        return $this->act_begining;
    }

    public function setActBegining(\DateTimeInterface $act_begining): self
    {
        $this->act_begining = $act_begining;

        return $this;
    }

    public function getActEnd(): ?\DateTimeInterface
    {
        return $this->act_end;
    }

    public function setActEnd(\DateTimeInterface $act_end): self
    {
        $this->act_end = $act_end;

        return $this;
    }

    public function getActContentFr(): ?string
    {
        return $this->act_content_fr;
    }

    public function setActContentFr(string $act_content_fr): self
    {
        $this->act_content_fr = $act_content_fr;

        return $this;
    }

    public function getActContentEn(): ?string
    {
        return $this->act_content_en;
    }

    public function setActContentEn(?string $act_content_en): self
    {
        $this->act_content_en = $act_content_en;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        // set the owning side of the relation if necessary
        if ($event->getAction() !== $this) {
            $event->setAction($this);
        }

        $this->event = $event;

        return $this;
    }

    public function getVote(): ?Vote
    {
        return $this->vote;
    }

    public function setVote(Vote $vote): self
    {
        // set the owning side of the relation if necessary
        if ($vote->getAction() !== $this) {
            $vote->setAction($this);
        }

        $this->vote = $vote;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(Survey $survey): self
    {
        // set the owning side of the relation if necessary
        if ($survey->getAction() !== $this) {
            $survey->setAction($this);
        }

        $this->survey = $survey;

        return $this;
    }
}
