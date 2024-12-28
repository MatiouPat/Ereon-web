<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\CreateWorldController;
use App\Repository\WorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: WorldRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['world:readCollection']]
        ),
        new Post(
            controller: CreateWorldController::class,
            normalizationContext: ['groups' => ['world:readCollection']]
        ),
        new Get(
            normalizationContext: ['groups' => ['world:read'], "enable_max_depth" => true],
        )
    ],
    denormalizationContext: ['groups' => ['world:write']],
    paginationEnabled: false
)]
#[ApiFilter(SearchFilter::class, properties: ['connections.user.id' => 'exact'])]
class World
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["world:readCollection", "user:read", "connection:read", 'dice:read', "world:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["world:readCollection", "user:read", "world:write", "world:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('dice:read')]
    private ?string $serverIdentifier = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('dice:read')]
    private ?string $diceChannelIdentifier = null;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Connection::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(["world:readCollection", "world:read"])]
    #[MaxDepth(1)]
    private Collection $connections;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Personage::class, orphanRemoval: true)]
    #[Groups(["world:read"])]
    #[MaxDepth(1)]
    private Collection $personages;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Map::class, orphanRemoval: true)]
    #[Groups(["world:read"])]
    #[MaxDepth(1)]
    private Collection $maps;

    #[ORM\OneToOne(mappedBy: 'world', cascade: ['persist', 'remove'])]
    #[Groups(["world:read"])]
    private ?MusicPlayer $musicPlayer = null;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Attribute::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(["world:write", "world:read"])]
    #[MaxDepth(1)]
    private Collection $attributes;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Skill::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(["world:write", "world:read"])]
    #[MaxDepth(1)]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Point::class, orphanRemoval: true)]
    #[Groups(["world:read"])]
    #[MaxDepth(1)]
    private Collection $points;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Dice::class, orphanRemoval: true)]
    #[Groups(["world:read"])]
    private Collection $dices;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Spell::class, orphanRemoval: true)]
    private Collection $spells;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Currency::class, orphanRemoval: true)]
    private Collection $currencies;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Alteration::class, orphanRemoval: true)]
    private Collection $alterations;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: DamageOrResistanceType::class, orphanRemoval: true)]
    private Collection $damageOrResistanceTypes;

    #[ORM\OneToMany(mappedBy: 'world', targetEntity: ItemPrefab::class, orphanRemoval: true)]
    private Collection $itemPrefabs;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(["world:readCollection", "world:write"])]
    private ?Image $image = null;

    public function __construct()
    {
        $this->connections = new ArrayCollection();
        $this->personages = new ArrayCollection();
        $this->maps = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->points = new ArrayCollection();
        $this->dices = new ArrayCollection();
        $this->spells = new ArrayCollection();
        $this->currencies = new ArrayCollection();
        $this->alterations = new ArrayCollection();
        $this->damageOrResistanceTypes = new ArrayCollection();
        $this->itemPrefabs = new ArrayCollection();
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

    public function getServerIdentifier(): ?string
    {
        return $this->serverIdentifier;
    }

    public function setServerIdentifier(?string $serverIdentifier): static
    {
        $this->serverIdentifier = $serverIdentifier;

        return $this;
    }

    public function getDiceChannelIdentifier(): ?string
    {
        return $this->diceChannelIdentifier;
    }

    public function setDiceChannelIdentifier(?string $diceChannelIdentifier): static
    {
        $this->diceChannelIdentifier = $diceChannelIdentifier;

        return $this;
    }

    /**
     * @return Collection<int, Connection>
     */
    public function getConnections(): Collection
    {
        return $this->connections;
    }

    public function addConnection(Connection $connection): self
    {
        if (!$this->connections->contains($connection)) {
            $this->connections->add($connection);
            $connection->setWorld($this);
        }

        return $this;
    }

    public function removeConnection(Connection $connection): self
    {
        if ($this->connections->removeElement($connection)) {
            // set the owning side to null (unless already changed)
            if ($connection->getWorld() === $this) {
                $connection->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Personage>
     */
    public function getPersonages(): Collection
    {
        return $this->personages;
    }

    public function addPersonage(Personage $personage): self
    {
        if (!$this->personages->contains($personage)) {
            $this->personages->add($personage);
            $personage->setWorld($this);
        }

        return $this;
    }

    public function removePersonage(Personage $personage): self
    {
        if ($this->personages->removeElement($personage)) {
            // set the owning side to null (unless already changed)
            if ($personage->getWorld() === $this) {
                $personage->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Map>
     */
    public function getMaps(): Collection
    {
        return $this->maps;
    }

    public function addMap(Map $map): self
    {
        if (!$this->maps->contains($map)) {
            $this->maps->add($map);
            $map->setWorld($this);
        }

        return $this;
    }

    public function removeMap(Map $map): self
    {
        if ($this->maps->removeElement($map)) {
            // set the owning side to null (unless already changed)
            if ($map->getWorld() === $this) {
                $map->setWorld(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getMusicPlayer(): ?MusicPlayer
    {
        return $this->musicPlayer;
    }

    public function setMusicPlayer(MusicPlayer $musicPlayer): self
    {
        // set the owning side of the relation if necessary
        if ($musicPlayer->getWorld() !== $this) {
            $musicPlayer->setWorld($this);
        }

        $this->musicPlayer = $musicPlayer;

        return $this;
    }

    /**
     * @return Collection<int, Attribute>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
            $attribute->setWorld($this);
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        if ($this->attributes->removeElement($attribute)) {
            // set the owning side to null (unless already changed)
            if ($attribute->getWorld() === $this) {
                $attribute->setWorld(null);
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
            $skill->setWorld($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getWorld() === $this) {
                $skill->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Point>
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    public function addPoint(Point $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points->add($point);
            $point->setWorld($this);
        }

        return $this;
    }

    public function removePoint(Point $point): self
    {
        if ($this->points->removeElement($point)) {
            // set the owning side to null (unless already changed)
            if ($point->getWorld() === $this) {
                $point->setWorld(null);
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
            $dice->setWorld($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): static
    {
        if ($this->dices->removeElement($dice)) {
            // set the owning side to null (unless already changed)
            if ($dice->getWorld() === $this) {
                $dice->setWorld(null);
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
            $spell->setWorld($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): static
    {
        if ($this->spells->removeElement($spell)) {
            // set the owning side to null (unless already changed)
            if ($spell->getWorld() === $this) {
                $spell->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Currency>
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    public function addCurrency(Currency $currency): static
    {
        if (!$this->currencies->contains($currency)) {
            $this->currencies->add($currency);
            $currency->setWorld($this);
        }

        return $this;
    }

    public function removeCurrency(Currency $currency): static
    {
        if ($this->currencies->removeElement($currency)) {
            // set the owning side to null (unless already changed)
            if ($currency->getWorld() === $this) {
                $currency->setWorld(null);
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
            $alteration->setWorld($this);
        }

        return $this;
    }

    public function removeAlteration(Alteration $alteration): static
    {
        if ($this->alterations->removeElement($alteration)) {
            // set the owning side to null (unless already changed)
            if ($alteration->getWorld() === $this) {
                $alteration->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DamageOrResistanceType>
     */
    public function getDamageOrResistanceTypes(): Collection
    {
        return $this->damageOrResistanceTypes;
    }

    public function addDamageOrResistanceType(DamageOrResistanceType $damageOrResistanceType): static
    {
        if (!$this->damageOrResistanceTypes->contains($damageOrResistanceType)) {
            $this->damageOrResistanceTypes->add($damageOrResistanceType);
            $damageOrResistanceType->setWorld($this);
        }

        return $this;
    }

    public function removeDamageOrResistanceType(DamageOrResistanceType $damageOrResistanceType): static
    {
        if ($this->damageOrResistanceTypes->removeElement($damageOrResistanceType)) {
            // set the owning side to null (unless already changed)
            if ($damageOrResistanceType->getWorld() === $this) {
                $damageOrResistanceType->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ItemPrefab>
     */
    public function getItemPrefabs(): Collection
    {
        return $this->itemPrefabs;
    }

    public function addItemPrefab(ItemPrefab $itemPrefab): static
    {
        if (!$this->itemPrefabs->contains($itemPrefab)) {
            $this->itemPrefabs->add($itemPrefab);
            $itemPrefab->setWorld($this);
        }

        return $this;
    }

    public function removeItemPrefab(ItemPrefab $itemPrefab): static
    {
        if ($this->itemPrefabs->removeElement($itemPrefab)) {
            // set the owning side to null (unless already changed)
            if ($itemPrefab->getWorld() === $this) {
                $itemPrefab->setWorld(null);
            }
        }

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
}
