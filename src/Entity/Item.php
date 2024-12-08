<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'item')]
    private Collection $categories;

    /**
     * @var Collection<int, Synergy>
     */
    #[ORM\ManyToMany(targetEntity: Synergy::class, mappedBy: 'item')]
    private Collection $synergies;

    /**
     * @var Collection<int, ItemList>
     */
    #[ORM\ManyToMany(targetEntity: ItemList::class, mappedBy: 'item')]
    private Collection $itemLists;

    /**
     * @var Collection<int, Build>
     */
    #[ORM\ManyToMany(targetEntity: Build::class, mappedBy: 'item')]
    private Collection $builds;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->synergies = new ArrayCollection();
        $this->itemLists = new ArrayCollection();
        $this->builds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addItem($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeItem($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Synergy>
     */
    public function getSynergies(): Collection
    {
        return $this->synergies;
    }

    public function addSynergy(Synergy $synergy): static
    {
        if (!$this->synergies->contains($synergy)) {
            $this->synergies->add($synergy);
            $synergy->addItem($this);
        }

        return $this;
    }

    public function removeSynergy(Synergy $synergy): static
    {
        if ($this->synergies->removeElement($synergy)) {
            $synergy->removeItem($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ItemList>
     */
    public function getItemLists(): Collection
    {
        return $this->itemLists;
    }

    public function addItemList(ItemList $itemList): static
    {
        if (!$this->itemLists->contains($itemList)) {
            $this->itemLists->add($itemList);
            $itemList->addItem($this);
        }

        return $this;
    }

    public function removeItemList(ItemList $itemList): static
    {
        if ($this->itemLists->removeElement($itemList)) {
            $itemList->removeItem($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Build>
     */
    public function getBuilds(): Collection
    {
        return $this->builds;
    }

    public function addBuild(Build $build): static
    {
        if (!$this->builds->contains($build)) {
            $this->builds->add($build);
            $build->addItem($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): static
    {
        if ($this->builds->removeElement($build)) {
            $build->removeItem($this);
        }

        return $this;
    }
}
