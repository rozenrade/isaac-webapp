<?php

namespace App\Entity;

use App\Repository\BossRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BossRepository::class)]
class Boss
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $Filename = null;

    /**
     * @var Collection<int, Build>
     */
    #[ORM\ManyToMany(targetEntity: Build::class, mappedBy: 'boss_id')]
    private Collection $builds;

    public function __construct()
    {
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
        return $this->Filename;
    }

    public function setFilename(string $Filename): static
    {
        $this->Filename = $Filename;

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
            $build->addBoss($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): static
    {
        if ($this->builds->removeElement($build)) {
            $build->removeBoss($this);
        }

        return $this;
    }

}
