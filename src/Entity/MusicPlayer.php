<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MusicPlayerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicPlayerRepository::class)]
#[ApiResource]
class MusicPlayer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isPlaying = null;

    #[ORM\Column]
    private ?bool $isLooping = null;

    #[ORM\OneToOne(inversedBy: 'musicPlayer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\ManyToOne(inversedBy: 'musicPlayers')]
    private ?Music $currentMusic = null;

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
}
