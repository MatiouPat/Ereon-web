<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NumberOfStatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NumberOfStatRepository::class)]
#[ApiResource(operations: [])]
class NumberOfStat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("person:read")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("person:read")]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfStats')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("person:read")]
    private ?Stat $stat = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Personage $personage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getStat(): ?Stat
    {
        return $this->stat;
    }

    public function setStat(?Stat $stat): self
    {
        $this->stat = $stat;

        return $this;
    }

    public function getPersonage(): ?Personage
    {
        return $this->personage;
    }

    public function setPersonage(?Personage $personage): self
    {
        $this->personage = $personage;

        return $this;
    }
}
