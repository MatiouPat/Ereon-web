<?php

namespace App\Entity;

use App\Repository\ConnectionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConnectionRepository::class)]
class Connection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["world:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("world:read")]
    private ?bool $isGameMaster = null;

    #[ORM\Column]
    #[Groups("world:read")]
    private ?bool $isConnected = null;

    #[ORM\ManyToOne(inversedBy: 'connections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\ManyToOne(inversedBy: 'connections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("world:read")]
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

    public function isIsConnected(): ?bool
    {
        return $this->isConnected;
    }

    public function setIsConnected(bool $isConnected): self
    {
        $this->isConnected = $isConnected;

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
