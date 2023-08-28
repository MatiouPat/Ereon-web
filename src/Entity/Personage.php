<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PersonageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonageRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['personage:read']],
    operations: [
        new GetCollection()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['user.id' => 'exact', 'world.id' => 'exact', 'user.discordIdentifier' => 'exact'])]
class Personage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["personage:read", 'dice:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["personage:read"])]
    private ?string $race = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["personage:read"])]
    private ?string $alignment = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["personage:read"])]
    private ?string $class = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["personage:read"])]
    private ?string $inventory = null;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: Dice::class, orphanRemoval: true)]
    private Collection $dices;

    #[ORM\ManyToOne(inversedBy: 'personages')]
    #[Groups(["personage:read"])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'personages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read"])]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: NumberOfAttribute::class, orphanRemoval: true)]
    #[Groups(["personage:read"])]
    private Collection $numberOfAttributes;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: NumberOfSkill::class, orphanRemoval: true)]
    #[Groups(["personage:read"])]
    private Collection $numberOfSkills;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: NumberOfPoint::class, orphanRemoval: true)]
    #[Groups(["personage:read"])]
    private Collection $numberOfPoints;

    public function __construct()
    {
        $this->dices = new ArrayCollection();
        $this->numberOfAttributes = new ArrayCollection();
        $this->numberOfSkills = new ArrayCollection();
        $this->numberOfPoints = new ArrayCollection();
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
            $dice->setPersonage($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): self
    {
        if ($this->dices->removeElement($dice)) {
            // set the owning side to null (unless already changed)
            if ($dice->getPersonage() === $this) {
                $dice->setPersonage(null);
            }
        }

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
}
