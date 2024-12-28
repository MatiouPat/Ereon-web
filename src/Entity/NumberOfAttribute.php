<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use App\Repository\NumberOfAttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NumberOfAttributeRepository::class)]
#[ApiResource(
    operations: [
        new Patch()
    ]
)]
class NumberOfAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read", "world:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["personage:read", 'personage:write', "world:read"])]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfAttributes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read", 'personage:write', "world:read"])]
    private ?Attribute $attribute = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfAttributes')]
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

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(?Attribute $attribute): self
    {
        $this->attribute = $attribute;

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
