<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StatRepository::class)]
#[ApiResource(
    operations: [],
    mercure: true
)]
class Stat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("person:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("person:read")]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $depth = null;

    #[ORM\OneToMany(mappedBy: 'stat', targetEntity: Dice::class)]
    private Collection $dices;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childStats')]
    private ?self $parentStat = null;

    #[ORM\OneToMany(mappedBy: 'parentStat', targetEntity: self::class)]
    private Collection $childStats;

    #[ORM\OneToMany(mappedBy: 'stat', targetEntity: NumberOfStat::class, orphanRemoval: true)]
    private Collection $numberOfStats;

    public function __construct()
    {
        $this->dices = new ArrayCollection();
        $this->childStats = new ArrayCollection();
        $this->numberOfStats = new ArrayCollection();
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

    /**
     * @return Collection<int, NumberOfStat>
     */
    public function getNumberOfStats(): Collection
    {
        return $this->numberOfStats;
    }

    public function addNumberOfStat(NumberOfStat $numberOfStat): self
    {
        if (!$this->numberOfStats->contains($numberOfStat)) {
            $this->numberOfStats->add($numberOfStat);
            $numberOfStat->setStat($this);
        }

        return $this;
    }

    public function removeNumberOfStat(NumberOfStat $numberOfStat): self
    {
        if ($this->numberOfStats->removeElement($numberOfStat)) {
            // set the owning side to null (unless already changed)
            if ($numberOfStat->getStat() === $this) {
                $numberOfStat->setStat(null);
            }
        }

        return $this;
    }
}
