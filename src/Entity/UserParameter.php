<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use App\Repository\UserParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: UserParameterRepository::class)]
#[ApiResource(
    operations: [
        new Patch()
    ]
)]
class UserParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("user:read")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("user:read")]
    private ?bool $isDarkTheme = null;

    #[ORM\Column]
    #[Groups("user:read")]
    #[Range(
        min: 0,
        max: 1,
        notInRangeMessage: 'You must be between {{ min }} and {{ max }}'
    )]
    private ?float $globalVolume = null;

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

    public function getGlobalVolume(): ?float
    {
        return $this->globalVolume;
    }

    public function setGlobalVolume(float $globalVolume): static
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
