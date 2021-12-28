<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventsRepository::class)
 */
class Events
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
    private $eve_created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $eve_begining;

    /**
     * @ORM\Column(type="datetime")
     */
    private $eve_end;

    /**
     * @ORM\Column(type="text")
     */
    private $eve_content_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $eve_content_en;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eve_location_osm;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Attends::class, mappedBy="event", orphanRemoval=true)
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

    public function getEveCreatedAt(): ?\DateTimeInterface
    {
        return $this->eve_created_at;
    }

    public function setEveCreatedAt(\DateTimeInterface $eve_created_at): self
    {
        $this->eve_created_at = $eve_created_at;

        return $this;
    }

    public function getEveBegining(): ?\DateTimeInterface
    {
        return $this->eve_begining;
    }

    public function setEveBegining(\DateTimeInterface $eve_begining): self
    {
        $this->eve_begining = $eve_begining;

        return $this;
    }

    public function getEveEnd(): ?\DateTimeInterface
    {
        return $this->eve_end;
    }

    public function setEveEnd(\DateTimeInterface $eve_end): self
    {
        $this->eve_end = $eve_end;

        return $this;
    }

    public function getEveContentFr(): ?string
    {
        return $this->eve_content_fr;
    }

    public function setEveContentFr(string $eve_content_fr): self
    {
        $this->eve_content_fr = $eve_content_fr;

        return $this;
    }

    public function getEveContentEn(): ?string
    {
        return $this->eve_content_en;
    }

    public function setEveContentEn(?string $eve_content_en): self
    {
        $this->eve_content_en = $eve_content_en;

        return $this;
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
     * @return Collection|Attends[]
     */
    public function getAttends(): Collection
    {
        return $this->attends;
    }

    public function addAttend(Attends $attend): self
    {
        if (!$this->attends->contains($attend)) {
            $this->attends[] = $attend;
            $attend->setEvent($this);
        }

        return $this;
    }

    public function removeAttend(Attends $attend): self
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
