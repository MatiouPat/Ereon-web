<?php

namespace App\Entity;

use App\Repository\WorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorldRepository::class)]
class World
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Connection::class, orphanRemoval: true)]
    private Collection $connections;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Personage::class, orphanRemoval: true)]
    private Collection $personages;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Map::class, orphanRemoval: true)]
    private Collection $maps;

    public function __construct()
    {
        $this->connections = new ArrayCollection();
        $this->personages = new ArrayCollection();
        $this->maps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Connection>
     */
    public function getConnections(): Collection
    {
        return $this->connections;
    }

    public function addConnection(Connection $connection): self
    {
        if (!$this->connections->contains($connection)) {
            $this->connections->add($connection);
            $connection->setWorld($this);
        }

        return $this;
    }

    public function removeConnection(Connection $connection): self
    {
        if ($this->connections->removeElement($connection)) {
            // set the owning side to null (unless already changed)
            if ($connection->getWorld() === $this) {
                $connection->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Personage>
     */
    public function getPersonages(): Collection
    {
        return $this->personages;
    }

    public function addPersonage(Personage $personage): self
    {
        if (!$this->personages->contains($personage)) {
            $this->personages->add($personage);
            $personage->setWorld($this);
        }

        return $this;
    }

    public function removePersonage(Personage $personage): self
    {
        if ($this->personages->removeElement($personage)) {
            // set the owning side to null (unless already changed)
            if ($personage->getWorld() === $this) {
                $personage->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Map>
     */
    public function getMaps(): Collection
    {
        return $this->maps;
    }

    public function addMap(Map $map): self
    {
        if (!$this->maps->contains($map)) {
            $this->maps->add($map);
            $map->setWorld($this);
        }

        return $this;
    }

    public function removeMap(Map $map): self
    {
        if ($this->maps->removeElement($map)) {
            // set the owning side to null (unless already changed)
            if ($map->getWorld() === $this) {
                $map->setWorld(null);
            }
        }

        return $this;
    }
}
