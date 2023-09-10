<?php

namespace App\Entity;

use App\Repository\DamageOrResistanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DamageOrResistanceRepository::class)]
class DamageOrResistance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'damageOrResistances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DamageOrResistanceType $type = null;

    #[ORM\ManyToOne(inversedBy: 'damages')]
    private ?Weapon $weapon = null;

    #[ORM\ManyToOne(inversedBy: 'resistances')]
    private ?Armor $armor = null;

    #[ORM\ManyToOne(inversedBy: 'damages')]
    private ?Spell $spell = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?DamageOrResistanceType
    {
        return $this->type;
    }

    public function setType(?DamageOrResistanceType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getWeapon(): ?Weapon
    {
        return $this->weapon;
    }

    public function setWeapon(?Weapon $weapon): static
    {
        $this->weapon = $weapon;

        return $this;
    }

    public function getArmor(): ?Armor
    {
        return $this->armor;
    }

    public function setArmor(?Armor $armor): static
    {
        $this->armor = $armor;

        return $this;
    }

    public function getSpell(): ?Spell
    {
        return $this->spell;
    }

    public function setSpell(?Spell $spell): static
    {
        $this->spell = $spell;

        return $this;
    }
}
