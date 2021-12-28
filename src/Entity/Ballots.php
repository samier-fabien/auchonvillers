<?php

namespace App\Entity;

use App\Repository\BallotsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BallotsRepository::class)
 */
class Ballots
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ballots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Votes::class, inversedBy="ballots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vote;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bal_vote;

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

    public function getVote(): ?Votes
    {
        return $this->vote;
    }

    public function setVote(?Votes $vote): self
    {
        $this->vote = $vote;

        return $this;
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
}
