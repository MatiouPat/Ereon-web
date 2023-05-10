<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['person:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'exact'])]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("person:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("person:read")]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Dice::class)]
    private Collection $dices;

    #[ORM\OneToOne(mappedBy: 'person', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: NumberOfStat::class, orphanRemoval: true)]
    #[Groups("person:read")]
    private Collection $numberOfStats;

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
            $dice->setPerson($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): self
    {
        if ($this->dices->removeElement($dice)) {
            // set the owning side to null (unless already changed)
            if ($dice->getPerson() === $this) {
                $dice->setPerson(null);
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
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setPerson(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getPerson() !== $this) {
            $user->setPerson($this);
        }

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
            $numberOfStat->setPerson($this);
        }

        return $this;
    }

    public function removeNumberOfStat(NumberOfStat $numberOfStat): self
    {
        if ($this->numberOfStats->removeElement($numberOfStat)) {
            // set the owning side to null (unless already changed)
            if ($numberOfStat->getPerson() === $this) {
                $numberOfStat->setPerson(null);
            }
        }

        return $this;
    }

}
