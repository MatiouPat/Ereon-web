<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\DamageOrResistanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DamageOrResistanceRepository::class)]
#[ApiResource(
    operations: [
        new Post()
    ]
)]
class DamageOrResistance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write', 'armorPrefab:read', 'armorPrefab:write', 'spell:read', 'spell:write'])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'damageOrResistances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write', 'armorPrefab:read', 'armorPrefab:write', 'spell:read', 'spell:write'])]
    private ?DamageOrResistanceType $type = null;

    #[ORM\ManyToOne(inversedBy: 'damages')]
    private ?Spell $spell = null;

    #[ORM\ManyToOne(inversedBy: 'damages')]
    private ?WeaponPrefab $weaponPrefab = null;

    #[ORM\ManyToOne(inversedBy: 'resistances')]
    private ?ArmorPrefab $armorPrefab = null;

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

    public function getSpell(): ?Spell
    {
        return $this->spell;
    }

    public function setSpell(?Spell $spell): static
    {
        $this->spell = $spell;

        return $this;
    }

    public function getWeaponPrefab(): ?WeaponPrefab
    {
        return $this->weaponPrefab;
    }

    public function setWeaponPrefab(?WeaponPrefab $weaponPrefab): static
    {
        $this->weaponPrefab = $weaponPrefab;

        return $this;
    }

    public function getArmorPrefab(): ?ArmorPrefab
    {
        return $this->armorPrefab;
    }

    public function setArmorPrefab(?ArmorPrefab $armorPrefab): static
    {
        $this->armorPrefab = $armorPrefab;

        return $this;
    }
}
