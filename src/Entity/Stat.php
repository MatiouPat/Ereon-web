<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatRepository::class)]
class Stat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $depth = null;

    #[ORM\OneToMany(mappedBy: 'stat', targetEntity: Dice::class)]
    private Collection $dices;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childStats')]
    private ?self $parentStat = null;

    #[ORM\OneToMany(mappedBy: 'parentStat', targetEntity: self::class)]
    private Collection $childStats;

    public function __construct()
    {
        $this->dices = new ArrayCollection();
        $this->childStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return Collection<int, Dice>
     */
    public function getDices(): Collection
    {
        return $this->dices;
    }

    public function addDice(Dice $dice): self
    {
        if (!$this->dices->contains($dice)) {
            $this->dices->add($dice);
            $dice->setStat($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): self
    {
        if ($this->dices->removeElement($dice)) {
            // set the owning side to null (unless already changed)
            if ($dice->getStat() === $this) {
                $dice->setStat(null);
            }
        }

        return $this;
    }

    public function getParentStat(): ?self
    {
        return $this->parentStat;
    }

    public function setParentStat(?self $parentStat): self
    {
        $this->parentStat = $parentStat;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildStats(): Collection
    {
        return $this->childStats;
    }

    public function addChildStat(self $childStat): self
    {
        if (!$this->childStats->contains($childStat)) {
            $this->childStats->add($childStat);
            $childStat->setParentStat($this);
        }

        return $this;
    }

    public function removeChildStat(self $childStat): self
    {
        if ($this->childStats->removeElement($childStat)) {
            // set the owning side to null (unless already changed)
            if ($childStat->getParentStat() === $this) {
                $childStat->setParentStat(null);
            }
        }

        return $this;
    }
}
