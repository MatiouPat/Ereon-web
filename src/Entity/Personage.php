<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PersonageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonageRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['personage:read']],
    denormalizationContext: ['groups' => ['personage:write']],
    operations: [
        new GetCollection(),
        new Post(),
        new Delete(),
        new Patch()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['user.id' => 'exact', 'world.id' => 'exact', 'user.discordIdentifier' => 'exact'])]
#[ApiFilter(ExistsFilter::class, properties: ['user'])]
class Personage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["personage:read", 'personage:write', 'dice:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["personage:read", 'personage:write'])]
    private ?string $race = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["personage:read", 'personage:write'])]
    private ?string $alignment = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["personage:read", 'personage:write'])]
    private ?string $class = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["personage:read", 'personage:write'])]
    private ?string $inventory = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["personage:read", 'personage:write'])]
    private ?string $biography = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(["personage:read", 'personage:write'])]
    private ?Image $image = null;

    #[ORM\ManyToOne(inversedBy: 'personages')]
    #[Groups(["personage:read", 'personage:write'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'personages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read", 'personage:write'])]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: NumberOfAttribute::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    #[Groups(["personage:read", 'personage:write'])]
    private Collection $numberOfAttributes;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: NumberOfSkill::class, orphanRemoval: true)]
    #[Groups(["personage:read"])]
    private Collection $numberOfSkills;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: NumberOfPoint::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    #[Groups(["personage:read", 'personage:write'])]
    private Collection $numberOfPoints;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: Dice::class, cascade: ["remove"])]
    private Collection $dices;

    #[ORM\ManyToMany(targetEntity: Spell::class, mappedBy: 'personages')]
    private Collection $spells;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: Item::class, orphanRemoval: true)]
    private Collection $items;

    #[ORM\ManyToMany(targetEntity: Alteration::class, mappedBy: 'personages')]
    private Collection $alterations;

    public function __construct()
    {
        $this->numberOfAttributes = new ArrayCollection();
        $this->numberOfSkills = new ArrayCollection();
        $this->numberOfPoints = new ArrayCollection();
        $this->dices = new ArrayCollection();
        $this->spells = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->alterations = new ArrayCollection();
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

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getAlignment(): ?string
    {
        return $this->alignment;
    }

    public function setAlignment(?string $alignment): self
    {
        $this->alignment = $alignment;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getInventory(): ?string
    {
        return $this->inventory;
    }

    public function setInventory(?string $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $numberOfAttribute->setPersonage($this);
        }

        return $this;
    }

    public function removeNumberOfAttribute(NumberOfAttribute $numberOfAttribute): self
    {
        if ($this->numberOfAttributes->removeElement($numberOfAttribute)) {
            // set the owning side to null (unless already changed)
            if ($numberOfAttribute->getPersonage() === $this) {
                $numberOfAttribute->setPersonage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NumberOfSkill>
     */
    public function getNumberOfSkills(): Collection
    {
        return $this->numberOfSkills;
    }

    public function addNumberOfSkill(NumberOfSkill $numberOfSkill): self
    {
        if (!$this->numberOfSkills->contains($numberOfSkill)) {
            $this->numberOfSkills->add($numberOfSkill);
            $numberOfSkill->setPersonage($this);
        }

        return $this;
    }

    public function removeNumberOfSkill(NumberOfSkill $numberOfSkill): self
    {
        if ($this->numberOfSkills->removeElement($numberOfSkill)) {
            // set the owning side to null (unless already changed)
            if ($numberOfSkill->getPersonage() === $this) {
                $numberOfSkill->setPersonage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NumberOfPoint>
     */
    public function getNumberOfPoints(): Collection
    {
        return $this->numberOfPoints;
    }

    public function addNumberOfPoint(NumberOfPoint $numberOfPoint): self
    {
        if (!$this->numberOfPoints->contains($numberOfPoint)) {
            $this->numberOfPoints->add($numberOfPoint);
            $numberOfPoint->setPersonage($this);
        }

        return $this;
    }

    public function removeNumberOfPoint(NumberOfPoint $numberOfPoint): self
    {
        if ($this->numberOfPoints->removeElement($numberOfPoint)) {
            // set the owning side to null (unless already changed)
            if ($numberOfPoint->getPersonage() === $this) {
                $numberOfPoint->setPersonage(null);
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

    public function addDice(Dice $dice): static
    {
        if (!$this->dices->contains($dice)) {
            $this->dices->add($dice);
            $dice->setPersonage($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): static
    {
        if ($this->dices->removeElement($dice)) {
            // set the owning side to null (unless already changed)
            if ($dice->getPersonage() === $this) {
                $dice->setPersonage(null);
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
            $spell->addPersonage($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): static
    {
        if ($this->spells->removeElement($spell)) {
            $spell->removePersonage($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setPersonage($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getPersonage() === $this) {
                $item->setPersonage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alteration>
     */
    public function getAlterations(): Collection
    {
        return $this->alterations;
    }

    public function addAlteration(Alteration $alteration): static
    {
        if (!$this->alterations->contains($alteration)) {
            $this->alterations->add($alteration);
            $alteration->addPersonage($this);
        }

        return $this;
    }

    public function removeAlteration(Alteration $alteration): static
    {
        if ($this->alterations->removeElement($alteration)) {
            $alteration->removePersonage($this);
        }

        return $this;
    }
}
