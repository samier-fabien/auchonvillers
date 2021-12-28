<?php

namespace App\Entity;

use App\Repository\AttendsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttendsRepository::class)
 */
class Attends
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="attends")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Events::class, inversedBy="attends")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

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

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): self
    {
        $this->event = $event;

        return $this;
    }
}
