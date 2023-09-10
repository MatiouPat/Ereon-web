<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $acronym = null;

    #[ORM\ManyToOne(inversedBy: 'currencies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: Money::class, orphanRemoval: true)]
    private Collection $money;

    public function __construct()
    {
        $this->money = new ArrayCollection();
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

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): static
    {
        $this->acronym = $acronym;

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
     * @return Collection<int, Money>
     */
    public function getMoney(): Collection
    {
        return $this->money;
    }

    public function addMoney(Money $money): static
    {
        if (!$this->money->contains($money)) {
            $this->money->add($money);
            $money->setCurrency($this);
        }

        return $this;
    }

    public function removeMoney(Money $money): static
    {
        if ($this->money->removeElement($money)) {
            // set the owning side to null (unless already changed)
            if ($money->getCurrency() === $this) {
                $money->setCurrency(null);
            }
        }

        return $this;
    }
}
