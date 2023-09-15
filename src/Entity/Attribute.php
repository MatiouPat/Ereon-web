<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\AttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AttributeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['attribute:read']],
    operations: [
        new GetCollection()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['world.id'  => 'exact'])]
class Attribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["attribute:read", "personage:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["attribute:read", "personage:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 3)]
    #[Groups(["attribute:read", "personage:read"])]
    private ?string $acronym = null;

    #[ORM\ManyToOne(inversedBy: 'attributes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: NumberOfAttribute::class, orphanRemoval: true)]
    private Collection $numberOfAttributes;

    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: Dice::class)]
    private Collection $dices;

    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: Skill::class)]
    private Collection $skills;

    #[ORM\ManyToMany(targetEntity: Spell::class, mappedBy: 'attributes')]
    private Collection $spells;

    #[ORM\ManyToMany(targetEntity: Weapon::class, mappedBy: 'attributes')]
    private Collection $weapons;

    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: AlterationChange::class)]
    private Collection $alterationChanges;

    public function __construct()
    {
        $this->numberOfAttributes = new ArrayCollection();
        $this->dices = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->spells = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->alterationChanges = new ArrayCollection();
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

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): static
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): self
    {
        $this->world = $world;

        return $this;
    }

    /**
     * @return Collection<int, NumberOfAttribute>
     */
    public function getNumberOfAttributes(): Collection
    {
        return $this->numberOfAttributes;
    }

    public function addNumberOfAttribute(NumberOfAttribute $numberOfAttribute): self
    {
        if (!$this->numberOfAttributes->contains($numberOfAttribute)) {
            $this->numberOfAttributes->add($numberOfAttribute);
            $numberOfAttribute->setAttribute($this);
        }

        return $this;
    }

    public function removeNumberOfAttribute(NumberOfAttribute $numberOfAttribute): self
    {
        if ($this->numberOfAttributes->removeElement($numberOfAttribute)) {
            // set the owning side to null (unless already changed)
            if ($numberOfAttribute->getAttribute() === $this) {
                $numberOfAttribute->setAttribute(null);
            }
        }

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
            $dice->setAttribute($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): self
    {
        if ($this->dices->removeElement($dice)) {
            // set the owning side to null (unless already changed)
            if ($dice->getAttribute() === $this) {
                $dice->setAttribute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setAttribute($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getAttribute() === $this) {
                $skill->setAttribute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Spell>
     */
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell): static
    {
        if (!$this->spells->contains($spell)) {
            $this->spells->add($spell);
            $spell->addAttribute($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): static
    {
        if ($this->spells->removeElement($spell)) {
            $spell->removeAttribute($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Weapon>
     */
    public function getWeapons(): Collection
    {
        return $this->weapons;
    }

    public function addWeapon(Weapon $weapon): static
    {
        if (!$this->weapons->contains($weapon)) {
            $this->weapons->add($weapon);
            $weapon->addAttribute($this);
        }

        return $this;
    }

    public function removeWeapon(Weapon $weapon): static
    {
        if ($this->weapons->removeElement($weapon)) {
            $weapon->removeAttribute($this);
        }

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
            $alterationChange->setAttribute($this);
        }

        return $this;
    }

    public function removeAlterationChange(AlterationChange $alterationChange): static
    {
        if ($this->alterationChanges->removeElement($alterationChange)) {
            // set the owning side to null (unless already changed)
            if ($alterationChange->getAttribute() === $this) {
                $alterationChange->setAttribute(null);
            }
        }

        return $this;
    }
}
