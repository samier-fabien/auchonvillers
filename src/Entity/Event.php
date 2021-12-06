<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $eve_location_osm;

    /**
     * @ORM\OneToOne(targetEntity=Action::class, inversedBy="event", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\OneToMany(targetEntity=Attend::class, mappedBy="event", orphanRemoval=true)
     */
    private $attends;

    public function __construct()
    {
        $this->attends = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEveLocationOsm(): ?string
    {
        return $this->eve_location_osm;
    }

    public function setEveLocationOsm(?string $eve_location_osm): self
    {
        $this->eve_location_osm = $eve_location_osm;

        return $this;
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

    /**
     * @return Collection|Attend[]
     */
    public function getAttends(): Collection
    {
        return $this->attends;
    }

    public function addAttend(Attend $attend): self
    {
        if (!$this->attends->contains($attend)) {
            $this->attends[] = $attend;
            $attend->setEvent($this);
        }

        return $this;
    }

    public function removeAttend(Attend $attend): self
    {
        if ($this->attends->removeElement($attend)) {
            // set the owning side to null (unless already changed)
            if ($attend->getEvent() === $this) {
                $attend->setEvent(null);
            }
        }

        return $this;
    }
}
