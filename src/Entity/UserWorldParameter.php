<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\UserWorldParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserWorldParameterRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['userWorldParameter:read']],
    operations: [
        new GetCollection(),
        new Patch()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['user.id' => 'exact', 'world.id' => 'exact'])]
class UserWorldParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('userWorldParameter:read')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('userWorldParameter:read')]
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
