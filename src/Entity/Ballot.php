<?php

namespace App\Entity;

use App\Repository\BallotRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BallotRepository::class)
 */
class Ballot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bal_vote;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ballots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Vote::class, inversedBy="ballots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vote;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalVote(): ?bool
    {
        return $this->bal_vote;
    }

    public function setBalVote(bool $bal_vote): self
    {
        $this->bal_vote = $bal_vote;

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

    public function getVote(): ?Vote
    {
        return $this->vote;
    }

    public function setVote(?Vote $vote): self
    {
        $this->vote = $vote;

        return $this;
    }
}
