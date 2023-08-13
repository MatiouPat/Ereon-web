<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PersonageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonageRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['personage:read']],
    operations: [
        new GetCollection()
    ],
    mercure: true
)]
#[ApiFilter(SearchFilter::class, properties: ['user.id' => 'exact', 'world.id' => 'exact'])]
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

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: Dice::class, orphanRemoval: true)]
    private Collection $dices;

    #[ORM\ManyToOne(inversedBy: 'personages')]
    #[Groups(["personage:read"])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: NumberOfStat::class, orphanRemoval: true)]
    private Collection $numberOfStats;

    #[ORM\ManyToOne(inversedBy: 'personages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read"])]
    private ?World $world = null;

    public function __construct()
    {
        $this->dices = new ArrayCollection();
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
            $numberOfStat->setPersonage($this);
        }

        return $this;
    }

    public function removeNumberOfStat(NumberOfStat $numberOfStat): self
    {
        if ($this->numberOfStats->removeElement($numberOfStat)) {
            // set the owning side to null (unless already changed)
            if ($numberOfStat->getPersonage() === $this) {
                $numberOfStat->setPersonage(null);
            }
        }

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
}
