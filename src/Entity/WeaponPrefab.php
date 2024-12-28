<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\WeaponPrefabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WeaponPrefabRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post()
    ],
    normalizationContext: ['groups' => ['weaponPrefab:read']],
    denormalizationContext: ['groups' => ['weaponPrefab:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['world.id' => 'exact'])]
class WeaponPrefab extends ItemPrefab
{

    #[ORM\Column(nullable: true)]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
    private ?int $scope = null;

    #[ORM\OneToMany(mappedBy: 'weaponPrefab', targetEntity: DamageOrResistance::class, cascade: ['persist', 'remove'])]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
    private Collection $damages;

    #[ORM\ManyToMany(targetEntity: Attribute::class, inversedBy: 'weaponPrefabs')]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
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

    public function setScope(?int $scope): static
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
            $damage->setWeaponPrefab($this);
        }

        return $this;
    }

    public function removeDamage(DamageOrResistance $damage): static
    {
        if ($this->damages->removeElement($damage)) {
            // set the owning side to null (unless already changed)
            if ($damage->getWeaponPrefab() === $this) {
                $damage->setWeaponPrefab(null);
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
