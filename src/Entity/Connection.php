<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\ConnectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConnectionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['connection:read']],
    operations: [
        new GetCollection(),
        new Patch()
    ]
)]
#[ApiFilter(DateFilter::class, properties: ['lastConnectionAt'])]
#[ApiFilter(SearchFilter::class, properties: ['world.id'  => 'exact'])]
class Connection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["connection:read", "world:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("world:read")]
    private ?bool $isGameMaster = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["connection:read", "world:read"])]
    private ?\DateTimeInterface $lastConnectionAt = null;

    #[ORM\ManyToOne(inversedBy: 'connections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("connection:read")]
    private ?World $world = null;

    #[ORM\ManyToOne(inversedBy: 'connections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["connection:read", "world:read"])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'connections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("world:read")]
    private ?Map $currentMap = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsGameMaster(): ?bool
    {
        return $this->isGameMaster;
    }

    public function setIsGameMaster(bool $isGameMaster): self
    {
        $this->isGameMaster = $isGameMaster;

        return $this;
    }

    public function getLastConnectionAt(): ?\DateTimeInterface
    {
        return $this->lastConnectionAt;
    }

    public function setLastConnectionAt(\DateTimeInterface $lastConnectionAt): self
    {
        $this->lastConnectionAt = $lastConnectionAt;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCurrentMap(): ?Map
    {
        return $this->currentMap;
    }

    public function setCurrentMap(?Map $currentMap): self
    {
        $this->currentMap = $currentMap;

        return $this;
    }
}
