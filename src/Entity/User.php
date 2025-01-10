<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column()]
    private array $roles = [];

    /**
     * @var Collection<int, Synergy>
     */
    #[ORM\OneToMany(targetEntity: Synergy::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $synergies;

    /**
     * @var Collection<int, ItemList>
     */
    #[ORM\OneToMany(targetEntity: ItemList::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $itemLists;

    /**
     * @var Collection<int, Build>
     */
    #[ORM\OneToMany(targetEntity: Build::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $builds;



    public function __construct()
    {
        $this->synergies = new ArrayCollection();
        $this->itemLists = new ArrayCollection();
        $this->builds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function setRoles(array $roles): static
    {
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        $this->roles = array_unique($roles);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';
        // Ceci est un rôle par défault
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here (e.g. the password)
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
            $synergy->setUtilisateur($this);
        }

        return $this;
    }

    public function removeSynergy(Synergy $synergy): static
    {
        if ($this->synergies->removeElement($synergy)) {
            // set the owning side to null (unless already changed)
            if ($synergy->getUtilisateur() === $this) {
                $synergy->setUtilisateur(null);
            }
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
            $itemList->setUtilisateur($this);
        }

        return $this;
    }

    public function removeItemList(ItemList $itemList): static
    {
        if ($this->itemLists->removeElement($itemList)) {
            // set the owning side to null (unless already changed)
            if ($itemList->getUtilisateur() === $this) {
                $itemList->setUtilisateur(null);
            }
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
            $build->setUtilisateur($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): static
    {
        if ($this->builds->removeElement($build)) {
            // set the owning side to null (unless already changed)
            if ($build->getUtilisateur() === $this) {
                $build->setUtilisateur(null);
            }
        }

        return $this;
    }
}
