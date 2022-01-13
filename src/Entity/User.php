<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_modification;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $newsletter = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vote = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $event = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $survey = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rgpd = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $user_terms_of_use = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $employee_terms_of_use = false;

    // /**
    //  * @ORM\OneToMany(targetEntity=Merchant::class, mappedBy="user")
    //  */
    // private $merchants;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Newsletter::class, mappedBy="user")
     */
    private $newsletters;

    // /**
    //  * @ORM\OneToMany(targetEntity=Action::class, mappedBy="user")
    //  */
    // private $actions;

    // /**
    //  * @ORM\OneToMany(targetEntity=Attend::class, mappedBy="user", orphanRemoval=true)
    //  */
    // private $attends;

    // /**
    //  * @ORM\OneToMany(targetEntity=Ballot::class, mappedBy="user", orphanRemoval=true)
    //  */
    // private $ballots;

    // /**
    //  * @ORM\OneToMany(targetEntity=Opinion::class, mappedBy="user", orphanRemoval=true)
    //  */
    // private $opinions;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="user", orphanRemoval=true)
     */
    private $chats;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Events::class, mappedBy="user", orphanRemoval=true)
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=Attends::class, mappedBy="user", orphanRemoval=true)
     */
    private $attends;

    /**
     * @ORM\OneToMany(targetEntity=Votes::class, mappedBy="user", orphanRemoval=true)
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity=Ballots::class, mappedBy="user", orphanRemoval=true)
     */
    private $ballots;

    /**
     * @ORM\OneToMany(targetEntity=Surveys::class, mappedBy="user", orphanRemoval=true)
     */
    private $surveys;

    /**
     * @ORM\OneToMany(targetEntity=Opinions::class, mappedBy="user", orphanRemoval=true)
     */
    private $opinions;

    public function __construct()
    {
        $this->merchants = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
        //$this->actions = new ArrayCollection();
        //$this->attends = new ArrayCollection();
        //$this->ballots = new ArrayCollection();
        //$this->opinions = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->surveys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLastModification(): ?\DateTimeInterface
    {
        return $this->last_modification;
    }

    public function setLastModification(\DateTimeInterface $last_modification): self
    {
        $this->last_modification = $last_modification;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter = false): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getVote(): ?bool
    {
        return $this->vote;
    }

    public function setVote(bool $vote = false): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getEvent(): ?bool
    {
        return $this->event;
    }

    public function setEvent(bool $event = false): self
    {
        $this->event = $event;

        return $this;
    }

    public function getSurvey(): ?bool
    {
        return $this->survey;
    }

    public function setSurvey(bool $survey = false): self
    {
        $this->survey = $survey;

        return $this;
    }

    public function getRgpd(): ?bool
    {
        return $this->rgpd;
    }

    public function setRgpd(bool $rgpd = false): self
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    public function getUserTermsOfUse(): ?bool
    {
        return $this->user_terms_of_use;
    }

    public function setUserTermsOfUse(bool $user_terms_of_use = false): self
    {
        $this->user_terms_of_use = $user_terms_of_use;

        return $this;
    }

    public function getEmployeeTermsOfUse(): ?bool
    {
        return $this->employee_terms_of_use;
    }

    public function setEmployeeTermsOfUse(bool $employee_terms_of_use = false): self
    {
        $this->employee_terms_of_use = $employee_terms_of_use;

        return $this;
    }

    // /**
    //  * @return Collection|Merchant[]
    //  */
    // public function getMerchants(): Collection
    // {
    //     return $this->merchants;
    // }

    // public function addMerchant(Merchant $merchant): self
    // {
    //     if (!$this->merchants->contains($merchant)) {
    //         $this->merchants[] = $merchant;
    //         $merchant->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeMerchant(Merchant $merchant): self
    // {
    //     if ($this->merchants->removeElement($merchant)) {
    //         // set the owning side to null (unless already changed)
    //         if ($merchant->getUser() === $this) {
    //             $merchant->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Newsletter[]
     */
    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletter $newsletter): self
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters[] = $newsletter;
            $newsletter->setUser($this);
        }

        return $this;
    }

    public function removeNewsletter(Newsletter $newsletter): self
    {
        if ($this->newsletters->removeElement($newsletter)) {
            // set the owning side to null (unless already changed)
            if ($newsletter->getUser() === $this) {
                $newsletter->setUser(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection|Action[]
    //  */
    // public function getActions(): Collection
    // {
    //     return $this->actions;
    // }

    // public function addAction(Action $action): self
    // {
    //     if (!$this->actions->contains($action)) {
    //         $this->actions[] = $action;
    //         $action->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeAction(Action $action): self
    // {
    //     if ($this->actions->removeElement($action)) {
    //         // set the owning side to null (unless already changed)
    //         if ($action->getUser() === $this) {
    //             $action->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection|Attend[]
    //  */
    // public function getAttends(): Collection
    // {
    //     return $this->attends;
    // }

    // public function addAttend(Attend $attend): self
    // {
    //     if (!$this->attends->contains($attend)) {
    //         $this->attends[] = $attend;
    //         $attend->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeAttend(Attend $attend): self
    // {
    //     if ($this->attends->removeElement($attend)) {
    //         // set the owning side to null (unless already changed)
    //         if ($attend->getUser() === $this) {
    //             $attend->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection|Ballot[]
    //  */
    // public function getBallots(): Collection
    // {
    //     return $this->ballots;
    // }

    // public function addBallot(Ballot $ballot): self
    // {
    //     if (!$this->ballots->contains($ballot)) {
    //         $this->ballots[] = $ballot;
    //         $ballot->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeBallot(Ballot $ballot): self
    // {
    //     if ($this->ballots->removeElement($ballot)) {
    //         // set the owning side to null (unless already changed)
    //         if ($ballot->getUser() === $this) {
    //             $ballot->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection|Opinion[]
    //  */
    // public function getOpinions(): Collection
    // {
    //     return $this->opinions;
    // }

    // public function addOpinion(Opinion $opinion): self
    // {
    //     if (!$this->opinions->contains($opinion)) {
    //         $this->opinions[] = $opinion;
    //         $opinion->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeOpinion(Opinion $opinion): self
    // {
    //     if ($this->opinions->removeElement($opinion)) {
    //         // set the owning side to null (unless already changed)
    //         if ($opinion->getUser() === $this) {
    //             $opinion->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getUser() === $this) {
                $chat->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Events[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setUser($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

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
            $attend->setUser($this);
        }

        return $this;
    }

    public function removeAttend(Attends $attend): self
    {
        if ($this->attends->removeElement($attend)) {
            // set the owning side to null (unless already changed)
            if ($attend->getUser() === $this) {
                $attend->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Votes[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Votes $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setUser($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getUser() === $this) {
                $vote->setUser(null);
            }
        }

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
            $ballot->setUser($this);
        }

        return $this;
    }

    public function removeBallot(Ballots $ballot): self
    {
        if ($this->ballots->removeElement($ballot)) {
            // set the owning side to null (unless already changed)
            if ($ballot->getUser() === $this) {
                $ballot->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Surveys[]
     */
    public function getSurveys(): Collection
    {
        return $this->surveys;
    }

    public function addSurvey(Surveys $survey): self
    {
        if (!$this->surveys->contains($survey)) {
            $this->surveys[] = $survey;
            $survey->setUser($this);
        }

        return $this;
    }

    public function removeSurvey(Surveys $survey): self
    {
        if ($this->surveys->removeElement($survey)) {
            // set the owning side to null (unless already changed)
            if ($survey->getUser() === $this) {
                $survey->setUser(null);
            }
        }

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
            $opinion->setUser($this);
        }

        return $this;
    }

    public function removeOpinion(Opinions $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getUser() === $this) {
                $opinion->setUser(null);
            }
        }

        return $this;
    }
}
