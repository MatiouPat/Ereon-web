<?php

namespace App\Entity;

use App\Repository\AlterationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlterationRepository::class)]
class Alteration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'alteration', targetEntity: AlterationChange::class, orphanRemoval: true)]
    private Collection $alterationChanges;

    #[ORM\ManyToOne(inversedBy: 'alterations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\ManyToMany(targetEntity: Personage::class, inversedBy: 'alterations')]
    private Collection $personages;

    public function __construct()
    {
        $this->alterationChanges = new ArrayCollection();
        $this->personages = new ArrayCollection();
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
     * @return Collection<int, AlterationChange>
     */
    public function getAlterationChanges(): Collection
    {
        return $this->alterationChanges;
    }

    public function addAlterationChange(AlterationChange $alterationChange): static
    {
        if (!$this->alterationChanges->contains($alterationChange)) {
            $this->alterationChanges->add($alterationChange);
            $alterationChange->setAlteration($this);
        }

        return $this;
    }

    public function removeAlterationChange(AlterationChange $alterationChange): static
    {
        if ($this->alterationChanges->removeElement($alterationChange)) {
            // set the owning side to null (unless already changed)
            if ($alterationChange->getAlteration() === $this) {
                $alterationChange->setAlteration(null);
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

    /**
     * @return Collection<int, Personage>
     */
    public function getPersonages(): Collection
    {
        return $this->personages;
    }

    public function addPersonage(Personage $personage): static
    {
        if (!$this->personages->contains($personage)) {
            $this->personages->add($personage);
        }

        return $this;
    }

    public function removePersonage(Personage $personage): static
    {
        $this->personages->removeElement($personage);

        return $this;
    }
}
