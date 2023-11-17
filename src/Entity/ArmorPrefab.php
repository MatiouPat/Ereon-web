<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ArmorPrefabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArmorPrefabRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post()
    ]
)]
class ArmorPrefab extends ItemPrefab
{

    #[ORM\OneToMany(mappedBy: 'armorPrefab', targetEntity: DamageOrResistance::class)]
    private Collection $resistances;

    public function __construct()
    {
        $this->resistances = new ArrayCollection();
    }

    /**
     * @return Collection<int, DamageOrResistance>
     */
    public function getResistances(): Collection
    {
        return $this->resistances;
    }

    public function addResistance(DamageOrResistance $resistance): static
    {
        if (!$this->resistances->contains($resistance)) {
            $this->resistances->add($resistance);
            $resistance->setArmorPrefab($this);
        }

        return $this;
    }

    public function removeResistance(DamageOrResistance $resistance): static
    {
        if ($this->resistances->removeElement($resistance)) {
            // set the owning side to null (unless already changed)
            if ($resistance->getArmorPrefab() === $this) {
                $resistance->setArmorPrefab(null);
            }
        }

        return $this;
    }
}
