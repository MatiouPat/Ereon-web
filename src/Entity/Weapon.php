<?php

namespace App\Entity;

use App\Repository\WeaponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WeaponRepository::class)]
class Weapon extends ItemPrefab
{
    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $scope = null;

    #[ORM\OneToMany(mappedBy: 'weapon', targetEntity: DamageOrResistance::class)]
    #[Groups(["personage:read"])]
    private Collection $damages;

    #[ORM\ManyToMany(targetEntity: Attribute::class, inversedBy: 'weapons')]
    #[Groups(["personage:read"])]
    private Collection $attributes;

    public function __construct()
    {
        $this->damages = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    public function getScope(): ?int
    {
        return $this->scope;
    }

    public function setScope(int $scope): static
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return Collection<int, DamageOrResistance>
     */
    public function getDamages(): Collection
    {
        return $this->damages;
    }

    public function addDamage(DamageOrResistance $damage): static
    {
        if (!$this->damages->contains($damage)) {
            $this->damages->add($damage);
            $damage->setWeapon($this);
        }

        return $this;
    }

    public function removeDamage(DamageOrResistance $damage): static
    {
        if ($this->damages->removeElement($damage)) {
            // set the owning side to null (unless already changed)
            if ($damage->getWeapon() === $this) {
                $damage->setWeapon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Attribute>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute): static
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): static
    {
        $this->attributes->removeElement($attribute);

        return $this;
    }
}
