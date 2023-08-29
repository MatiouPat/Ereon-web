<?php

namespace App\Entity;

use App\Repository\WorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WorldRepository::class)]
class World
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["world:read", "user:read", "connection:read", "personage:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["world:read", "user:read"])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Connection::class, orphanRemoval: true)]
    #[Groups("world:read")]
    private Collection $connections;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Personage::class, orphanRemoval: true)]
    private Collection $personages;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Map::class, orphanRemoval: true)]
    private Collection $maps;

    #[ORM\OneToOne(mappedBy: 'world', cascade: ['persist', 'remove'])]
    private ?MusicPlayer $musicPlayer = null;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: UserWorldParameter::class, orphanRemoval: true)]
    private Collection $userWorldParameters;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Attribute::class, orphanRemoval: true)]
    private Collection $attributes;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Skill::class, orphanRemoval: true)]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Point::class, orphanRemoval: true)]
    private Collection $points;

    public function __construct()
    {
        $this->connections = new ArrayCollection();
        $this->personages = new ArrayCollection();
        $this->maps = new ArrayCollection();
        $this->userWorldParameters = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->points = new ArrayCollection();
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

    public function __toString()
    {
        return $this->name;
    }

    public function getMusicPlayer(): ?MusicPlayer
    {
        return $this->musicPlayer;
    }

    public function setMusicPlayer(MusicPlayer $musicPlayer): self
    {
        // set the owning side of the relation if necessary
        if ($musicPlayer->getWorld() !== $this) {
            $musicPlayer->setWorld($this);
        }

        $this->musicPlayer = $musicPlayer;

        return $this;
    }

    /**
     * @return Collection<int, UserWorldParameter>
     */
    public function getUserWorldParameters(): Collection
    {
        return $this->userWorldParameters;
    }

    public function addUserWorldParameter(UserWorldParameter $userWorldParameter): self
    {
        if (!$this->userWorldParameters->contains($userWorldParameter)) {
            $this->userWorldParameters->add($userWorldParameter);
            $userWorldParameter->setWorld($this);
        }

        return $this;
    }

    public function removeUserWorldParameter(UserWorldParameter $userWorldParameter): self
    {
        if ($this->userWorldParameters->removeElement($userWorldParameter)) {
            // set the owning side to null (unless already changed)
            if ($userWorldParameter->getWorld() === $this) {
                $userWorldParameter->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Attribute>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
            $attribute->setWorld($this);
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        if ($this->attributes->removeElement($attribute)) {
            // set the owning side to null (unless already changed)
            if ($attribute->getWorld() === $this) {
                $attribute->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setWorld($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getWorld() === $this) {
                $skill->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Point>
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    public function addPoint(Point $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points->add($point);
            $point->setWorld($this);
        }

        return $this;
    }

    public function removePoint(Point $point): self
    {
        if ($this->points->removeElement($point)) {
            // set the owning side to null (unless already changed)
            if ($point->getWorld() === $this) {
                $point->setWorld(null);
            }
        }

        return $this;
    }
}
