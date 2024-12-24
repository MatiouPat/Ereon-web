<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ApiResource(
    operations: []
)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read", "world:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["personage:read", "world:write", "world:read"])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'skills',)]
    #[Groups(["world:write", "world:read"])]
    private ?Attribute $attribute = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'skill', targetEntity: NumberOfSkill::class, orphanRemoval: true)]
    private Collection $numberOfSkills;

    #[ORM\OneToMany(mappedBy: 'skill', targetEntity: AlterationChange::class)]
    private Collection $alterationChanges;

    public function __construct()
    {
        $this->numberOfSkills = new ArrayCollection();
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

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(?Attribute $attribute): self
    {
        $this->attribute = $attribute;

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
            $numberOfSkill->setSkill($this);
        }

        return $this;
    }

    public function removeNumberOfSkill(NumberOfSkill $numberOfSkill): self
    {
        if ($this->numberOfSkills->removeElement($numberOfSkill)) {
            // set the owning side to null (unless already changed)
            if ($numberOfSkill->getSkill() === $this) {
                $numberOfSkill->setSkill(null);
            }
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
            $alterationChange->setSkill($this);
        }

        return $this;
    }

    public function removeAlterationChange(AlterationChange $alterationChange): static
    {
        if ($this->alterationChanges->removeElement($alterationChange)) {
            // set the owning side to null (unless already changed)
            if ($alterationChange->getSkill() === $this) {
                $alterationChange->setSkill(null);
            }
        }

        return $this;
    }
}
