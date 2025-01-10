<?php

namespace App\Entity;

use App\Repository\BuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildRepository::class)]
class Build
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Item::class, inversedBy: 'builds', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'build_item')]  // Nom de la table de liaison
    private Collection $item;

    #[ORM\ManyToOne(inversedBy: 'builds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'builds')]
    private Collection $character;

    /**
     * @var Collection<int, Boss>
     */
    #[ORM\ManyToMany(targetEntity: Boss::class, inversedBy: 'builds')]
    private Collection $boss;

    public function __construct()
    {
        $this->item = new ArrayCollection();
        $this->character = new ArrayCollection();
        $this->boss = new ArrayCollection();
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

    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(Item $item): static
    {
        if (!$this->item->contains($item)) {
            $this->item->add($item);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        $this->item->removeElement($item);

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacter(): Collection
    {
        return $this->character;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->character->contains($character)) {
            $this->character->add($character);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        $this->character->removeElement($character);

        return $this;
    }

    /**
     * @return Collection<int, Boss>
     */
    public function getBossId(): Collection
    {
        return $this->boss;
    }

    public function addBoss(Boss $boss): static
    {
        if (!$this->boss->contains($boss)) {
            $this->boss->add($boss);
        }

        return $this;
    }

    public function removeBoss(Boss $boss): static
    {
        $this->boss->removeElement($boss);

        return $this;
    }
}
