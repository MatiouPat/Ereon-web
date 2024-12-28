<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\MusicPlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: MusicPlayerRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Patch()
    ],
    normalizationContext: ['groups' => ['musicPlayer:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['world.id'  => 'exact'])]
class MusicPlayer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["musicPlayer:read", "world:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["musicPlayer:read", "world:read"])]
    private ?bool $isPlaying = null;

    #[ORM\Column]
    #[Groups(["musicPlayer:read", "world:read"])]
    private ?bool $isLooping = null;

    #[ORM\OneToOne(inversedBy: 'musicPlayer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\ManyToOne(inversedBy: 'musicPlayers')]
    #[Groups(["musicPlayer:read", "world:read"])]
    #[MaxDepth(1)]
    private ?Music $currentMusic = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["musicPlayer:read", "world:read"])]
    private ?float $currentTimePlay = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsPlaying(): ?bool
    {
        return $this->isPlaying;
    }

    public function setIsPlaying(bool $isPlaying): self
    {
        $this->isPlaying = $isPlaying;

        return $this;
    }

    public function isIsLooping(): ?bool
    {
        return $this->isLooping;
    }

    public function setIsLooping(bool $isLooping): self
    {
        $this->isLooping = $isLooping;

        return $this;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(World $world): self
    {
        $this->world = $world;

        return $this;
    }

    public function getCurrentMusic(): ?Music
    {
        return $this->currentMusic;
    }

    public function setCurrentMusic(?Music $currentMusic): self
    {
        $this->currentMusic = $currentMusic;

        return $this;
    }

    public function getCurrentTimePlay(): ?float
    {
        return $this->currentTimePlay;
    }

    public function setCurrentTimePlay(?float $currentTimePlay): self
    {
        $this->currentTimePlay = $currentTimePlay;

        return $this;
    }
}
