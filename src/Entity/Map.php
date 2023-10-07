<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\MapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MapRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Patch()
    ],
    normalizationContext: ['groups' => ['map:read']],
    paginationEnabled: false
)]
class Map
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["world:read", "map:read","user:read", "connection:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("map:read")]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups("map:read")]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups("map:read")]
    private ?int $height = null;

    #[ORM\Column]
    #[Groups("map:read")]
    private ?bool $hasDynamicLight = null;

    #[ORM\OneToMany(mappedBy: 'map', targetEntity: Token::class, orphanRemoval: true)]
    #[Groups("map:read")]
    private Collection $tokens;

    #[ORM\ManyToOne(inversedBy: 'maps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'currentMap', targetEntity: Connection::class)]
    #[Groups("map:read")]
    private Collection $connections;

    #[ORM\OneToMany(mappedBy: 'map', targetEntity: LightingWall::class, orphanRemoval: true)]
    #[Groups("map:read")]
    private Collection $lightingWalls;

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
        $this->connections = new ArrayCollection();
        $this->lightingWalls = new ArrayCollection();
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

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function isHasDynamicLight(): ?bool
    {
        return $this->hasDynamicLight;
    }

    public function setHasDynamicLight(bool $hasDynamicLight): self
    {
        $this->hasDynamicLight = $hasDynamicLight;

        return $this;
    }

    /**
     * @return Collection<int, Token>
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function addToken(Token $token): self
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens->add($token);
            $token->setMap($this);
        }

        return $this;
    }

    public function removeToken(Token $token): self
    {
        if ($this->tokens->removeElement($token)) {
            // set the owning side to null (unless already changed)
            if ($token->getMap() === $this) {
                $token->setMap(null);
            }
        }

        return $this;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): self
    {
        $this->world = $world;

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
            $connection->setCurrentMap($this);
        }

        return $this;
    }

    public function removeConnection(Connection $connection): self
    {
        if ($this->connections->removeElement($connection)) {
            // set the owning side to null (unless already changed)
            if ($connection->getCurrentMap() === $this) {
                $connection->setCurrentMap(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LightingWall>
     */
    public function getLightingWalls(): Collection
    {
        return $this->lightingWalls;
    }

    public function addLightingWall(LightingWall $lightingWall): static
    {
        if (!$this->lightingWalls->contains($lightingWall)) {
            $this->lightingWalls->add($lightingWall);
            $lightingWall->setMap($this);
        }

        return $this;
    }

    public function removeLightingWall(LightingWall $lightingWall): static
    {
        if ($this->lightingWalls->removeElement($lightingWall)) {
            // set the owning side to null (unless already changed)
            if ($lightingWall->getMap() === $this) {
                $lightingWall->setMap(null);
            }
        }

        return $this;
    }
}
