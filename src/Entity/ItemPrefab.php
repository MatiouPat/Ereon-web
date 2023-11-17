<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\ItemPrefabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ItemPrefabRepository::class)]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name:"item", type: "string")]
#[DiscriminatorMap(["item" => ItemPrefab::class, "armor" => ArmorPrefab::class, "weapon" => WeaponPrefab::class])]
#[ApiResource(
    operations: [
        new Post()
    ]
)]
class ItemPrefab
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['weaponPrefab:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'itemPrefab', targetEntity: Cost::class, orphanRemoval: true)]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
    private Collection $costs;

    #[ORM\OneToMany(mappedBy: 'itemPrefab', targetEntity: Item::class, orphanRemoval: true)]
    private Collection $items;

    #[ORM\ManyToOne(inversedBy: 'itemPrefabs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['weaponPrefab:write'])]
    private ?World $world = null;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
        $this->items = new ArrayCollection();
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

    /**
     * @return Collection<int, Cost>
     */
    public function getCosts(): Collection
    {
        return $this->costs;
    }

    public function addCost(Cost $cost): static
    {
        if (!$this->costs->contains($cost)) {
            $this->costs->add($cost);
            $cost->setItemPrefab($this);
        }

        return $this;
    }

    public function removeCost(Cost $cost): static
    {
        if ($this->costs->removeElement($cost)) {
            // set the owning side to null (unless already changed)
            if ($cost->getItemPrefab() === $this) {
                $cost->setItemPrefab(null);
            }
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
            $item->setItemPrefab($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getItemPrefab() === $this) {
                $item->setItemPrefab(null);
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
