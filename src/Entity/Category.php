<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cat_name_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cat_name_en;

    /**
     * @ORM\Column(type="text")
     */
    private $cat_description_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cat_description_en;

    /**
     * @ORM\Column(type="integer")
     */
    private $cat_order_of_appearance;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="category", orphanRemoval=true)
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatNameFr(): ?string
    {
        return $this->cat_name_fr;
    }

    public function setCatNameFr(string $cat_name_fr): self
    {
        $this->cat_name_fr = $cat_name_fr;

        return $this;
    }

    public function getCatNameEn(): ?string
    {
        return $this->cat_name_en;
    }

    public function setCatNameEn(?string $cat_name_en): self
    {
        $this->cat_name_en = $cat_name_en;

        return $this;
    }

    public function getCatDescriptionFr(): ?string
    {
        return $this->cat_description_fr;
    }

    public function setCatDescriptionFr(string $cat_description_fr): self
    {
        $this->cat_description_fr = $cat_description_fr;

        return $this;
    }

    public function getCatDescriptionEn(): ?string
    {
        return $this->cat_description_en;
    }

    public function setCatDescriptionEn(?string $cat_description_en): self
    {
        $this->cat_description_en = $cat_description_en;

        return $this;
    }

    public function getCatOrderOfAppearance(): ?int
    {
        return $this->cat_order_of_appearance;
    }

    public function setCatOrderOfAppearance(int $cat_order_of_appearance): self
    {
        $this->cat_order_of_appearance = $cat_order_of_appearance;

        return $this;
    }

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
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
