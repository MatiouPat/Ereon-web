<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\DiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiceRepository::class)]
#[ApiResource(
    operations: [
        new Post()
    ]
)]
class Dice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $diceNumber = null;

    #[ORM\Column]
    private ?int $brutValue = null;

    #[ORM\Column]
    private ?int $finalValue = null;

    #[ORM\Column(length: 255)]
    private ?string $computation = null;

    #[ORM\ManyToOne(inversedBy: 'dices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\ManyToOne(inversedBy: 'dices')]
    private ?Stat $stat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiceNumber(): ?int
    {
        return $this->diceNumber;
    }

    public function setDiceNumber(int $diceNumber): self
    {
        $this->diceNumber = $diceNumber;

        return $this;
    }

    public function getBrutValue(): ?int
    {
        return $this->brutValue;
    }

    public function setBrutValue(int $brutValue): self
    {
        $this->brutValue = $brutValue;

        return $this;
    }

    public function getFinalValue(): ?int
    {
        return $this->finalValue;
    }

    public function setFinalValue(int $finalValue): self
    {
        $this->finalValue = $finalValue;

        return $this;
    }

    public function getComputation(): ?string
    {
        return $this->computation;
    }

    public function setComputation(string $computation): self
    {
        $this->computation = $computation;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getStat(): ?Stat
    {
        return $this->stat;
    }

    public function setStat(?Stat $stat): self
    {
        $this->stat = $stat;

        return $this;
    }
}
