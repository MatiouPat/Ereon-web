<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\DiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;

#[ORM\Entity(repositoryClass: DiceRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['dice:read']],
    denormalizationContext: ['groups' => ['dice:write']],
    operations: [
        new GetCollection(),
        new Post()
    ],
    paginationEnabled: false
)]
class Dice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[GreaterThan(1)]
    #[LessThanOrEqual(100)]
    #[Groups('dice:read')]
    private ?int $diceNumber = null;

    #[ORM\Column]
    #[Groups('dice:read')]
    private ?int $brutValue = null;

    #[ORM\Column]
    #[Groups('dice:read')]
    private ?int $finalValue = null;

    #[ORM\Column(length: 255)]
    #[Regex(
        pattern: '/^([1-9]\d*)?d([1-9]\d?|100)([\-\+][1-9]\d*)*$/',
        message: 'Cette valeur n\'est pas un jet de dé valide.'
    )]
    #[Groups(['dice:read', 'dice:write'])]
    #[ApiProperty(
        openapiContext: [
            'example' => "d100+20"
        ]
    )]
    private ?string $computation = null;

    #[ORM\ManyToOne(inversedBy: 'dices')]
    private ?Attribute $attribute = null;

    #[ORM\ManyToOne(inversedBy: 'dices')]
    #[Groups(['dice:read', 'dice:write'])]
    private ?Personage $personage = null;

    #[ORM\ManyToOne(inversedBy: 'dices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['dice:read', 'dice:write'])]
    private ?User $launcher = null;

    #[ORM\ManyToOne(inversedBy: 'dices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['dice:read', 'dice:write'])]
    private ?World $world = null;

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

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(?Attribute $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getPersonage(): ?Personage
    {
        return $this->personage;
    }

    public function setPersonage(?Personage $personage): static
    {
        $this->personage = $personage;

        return $this;
    }

    public function getLauncher(): ?User
    {
        return $this->launcher;
    }

    public function setLauncher(?User $launcher): static
    {
        $this->launcher = $launcher;

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
