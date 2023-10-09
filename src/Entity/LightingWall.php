<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use App\Repository\LightingWallRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LightingWallRepository::class)]
#[ApiResource(
    operations: [
        new Post(),
        new Delete()
    ]
)]
class LightingWall
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("map:read")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("map:read")]
    private ?int $startX = null;

    #[ORM\Column]
    #[Groups("map:read")]
    private ?int $endX = null;

    #[ORM\Column]
    #[Groups("map:read")]
    private ?int $startY = null;

    #[ORM\Column]
    #[Groups("map:read")]
    private ?int $endY = null;

    #[ORM\ManyToOne(inversedBy: 'lightingWalls')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Map $map = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartX(): ?int
    {
        return $this->startX;
    }

    public function setStartX(int $startX): static
    {
        $this->startX = $startX;

        return $this;
    }

    public function getEndX(): ?int
    {
        return $this->endX;
    }

    public function setEndX(int $endX): static
    {
        $this->endX = $endX;

        return $this;
    }

    public function getStartY(): ?int
    {
        return $this->startY;
    }

    public function setStartY(int $startY): static
    {
        $this->startY = $startY;

        return $this;
    }

    public function getEndY(): ?int
    {
        return $this->endY;
    }

    public function setEndY(int $endY): static
    {
        $this->endY = $endY;

        return $this;
    }

    public function getMap(): ?Map
    {
        return $this->map;
    }

    public function setMap(?Map $map): static
    {
        $this->map = $map;

        return $this;
    }
}
