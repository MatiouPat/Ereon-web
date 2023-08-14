<?php

namespace App\Entity;

use App\Repository\UserWorldParameterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserWorldParameterRepository::class)]
class UserWorldParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $musicVolume = null;

    #[ORM\ManyToOne(inversedBy: 'userWorldParameters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userWorldParameters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMusicVolume(): ?int
    {
        return $this->musicVolume;
    }

    public function setMusicVolume(int $musicVolume): self
    {
        $this->musicVolume = $musicVolume;

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

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): self
    {
        $this->world = $world;

        return $this;
    }
}
