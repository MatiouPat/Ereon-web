<?php

namespace App\Entity;

use App\Repository\AlterationChangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlterationChangeRepository::class)]
class AlterationChange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $value = null;

    #[ORM\Column(nullable: true)]
    private ?int $percentage = null;

    #[ORM\ManyToOne(inversedBy: 'alterationChanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Alteration $alteration = null;

    #[ORM\ManyToOne(inversedBy: 'alterationChanges')]
    private ?Attribute $attribute = null;

    #[ORM\ManyToOne(inversedBy: 'alterationChanges')]
    private ?Skill $skill = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getPercentage(): ?int
    {
        return $this->percentage;
    }

    public function setPercentage(?int $percentage): static
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getAlteration(): ?Alteration
    {
        return $this->alteration;
    }

    public function setAlteration(?Alteration $alteration): static
    {
        $this->alteration = $alteration;

        return $this;
    }

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(?Attribute $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): static
    {
        $this->skill = $skill;

        return $this;
    }
}
