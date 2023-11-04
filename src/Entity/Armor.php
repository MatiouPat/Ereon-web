<?php

namespace App\Entity;

use App\Repository\ArmorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArmorRepository::class)]
class Armor extends ItemPrefab
{
    #[ORM\OneToMany(mappedBy: 'armor', targetEntity: DamageOrResistance::class)]
    #[Groups(["personage:read"])]
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
            $resistance->setArmor($this);
        }

        return $this;
    }

    public function removeResistance(DamageOrResistance $resistance): static
    {
        if ($this->resistances->removeElement($resistance)) {
            // set the owning side to null (unless already changed)
            if ($resistance->getArmor() === $this) {
                $resistance->setArmor(null);
            }
        }

        return $this;
    }
}
