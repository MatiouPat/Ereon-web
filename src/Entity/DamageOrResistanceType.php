<?php

namespace App\Entity;

use App\Repository\DamageOrResistanceTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DamageOrResistanceTypeRepository::class)]
class DamageOrResistanceType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["personage:read"])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: DamageOrResistance::class, orphanRemoval: true)]
    private Collection $damageOrResistances;

    #[ORM\ManyToOne(inversedBy: 'damageOrResistanceTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    public function __construct()
    {
        $this->damageOrResistances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, DamageOrResistance>
     */
    public function getDamageOrResistances(): Collection
    {
        return $this->damageOrResistances;
    }

    public function addDamageOrResistance(DamageOrResistance $damageOrResistance): static
    {
        if (!$this->damageOrResistances->contains($damageOrResistance)) {
            $this->damageOrResistances->add($damageOrResistance);
            $damageOrResistance->setType($this);
        }

        return $this;
    }

    public function removeDamageOrResistance(DamageOrResistance $damageOrResistance): static
    {
        if ($this->damageOrResistances->removeElement($damageOrResistance)) {
            // set the owning side to null (unless already changed)
            if ($damageOrResistance->getType() === $this) {
                $damageOrResistance->setType(null);
            }
        }

        return $this;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): static
    {
        $this->world = $world;

        return $this;
    }
}
