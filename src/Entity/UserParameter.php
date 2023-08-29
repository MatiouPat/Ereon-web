<?php

namespace App\Entity;

use App\Repository\UserParameterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserParameterRepository::class)]
class UserParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isDarkTheme = null;

    #[ORM\Column]
    private ?int $globalVolume = null;

    #[ORM\OneToOne(inversedBy: 'userParameter', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsDarkTheme(): ?bool
    {
        return $this->isDarkTheme;
    }

    public function setIsDarkTheme(bool $isDarkTheme): self
    {
        $this->isDarkTheme = $isDarkTheme;

        return $this;
    }

    public function getGlobalVolume(): ?int
    {
        return $this->globalVolume;
    }

    public function setGlobalVolume(int $globalVolume): self
    {
        $this->globalVolume = $globalVolume;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
