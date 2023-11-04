<?php

namespace App\Entity;

use App\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SpellRepository::class)]
class Spell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["personage:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["personage:read"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $scope = null;

    #[ORM\ManyToMany(targetEntity: Attribute::class, inversedBy: 'spells')]
    #[Groups(["personage:read"])]
    private Collection $attributes;

    #[ORM\ManyToMany(targetEntity: Personage::class, inversedBy: 'spells')]
    private Collection $personages;

    #[ORM\ManyToOne(inversedBy: 'spells')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'spell', targetEntity: Expense::class, orphanRemoval: true)]
    #[Groups(["personage:read"])]
    private Collection $expenses;

    #[ORM\OneToMany(mappedBy: 'spell', targetEntity: DamageOrResistance::class)]
    #[Groups(["personage:read"])]
    private Collection $damages;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->personages = new ArrayCollection();
        $this->expenses = new ArrayCollection();
        $this->damages = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
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
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setSpell($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getSpell() === $this) {
                $expense->setSpell(null);
            }
        }

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
            $damage->setSpell($this);
        }

        return $this;
    }

    public function removeDamage(DamageOrResistance $damage): static
    {
        if ($this->damages->removeElement($damage)) {
            // set the owning side to null (unless already changed)
            if ($damage->getSpell() === $this) {
                $damage->setSpell(null);
            }
        }

        return $this;
    }
}
