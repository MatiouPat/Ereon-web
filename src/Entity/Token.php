<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\TokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post()
    ]
)]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("user:read")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("user:read")]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups("user:read")]
    private ?int $height = null;

    #[ORM\Column]
    #[Groups("user:read")]
    private ?int $topPosition = null;

    #[ORM\Column]
    #[Groups("user:read")]
    private ?int $leftPosition = null;

    #[ORM\ManyToMany(targetEntity: Map::class, inversedBy: 'tokens')]
    private Collection $maps;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    private ?Asset $asset = null;

    public function __construct()
    {
        $this->maps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTopPosition(): ?int
    {
        return $this->topPosition;
    }

    public function setTopPosition(int $topPosition): self
    {
        $this->topPosition = $topPosition;

        return $this;
    }

    public function getLeftPosition(): ?int
    {
        return $this->leftPosition;
    }

    public function setLeftPosition(int $leftPosition): self
    {
        $this->leftPosition = $leftPosition;

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
        }

        return $this;
    }

    public function removeMap(Map $map): self
    {
        $this->maps->removeElement($map);

        return $this;
    }

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }
}
