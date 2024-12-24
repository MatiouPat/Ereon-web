<?php

namespace App\Entity;

use App\Repository\NumberOfSkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NumberOfSkillRepository::class)]
class NumberOfSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read", "world:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["personage:read", "world:read"])]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfSkills')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read", "world:read"])]
    private ?Skill $skill = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfSkills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Personage $personage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getPersonage(): ?Personage
    {
        return $this->personage;
    }

    public function setPersonage(?Personage $personage): self
    {
        $this->personage = $personage;

        return $this;
    }
}
