<?php

namespace App\Entity;

use App\Repository\CostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CostRepository::class)]
class Cost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'costs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read", 'weaponPrefab:read', 'weaponPrefab:write'])]
    private ?Money $money = null;

    #[ORM\ManyToOne(inversedBy: 'costs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemPrefab $itemPrefab = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getMoney(): ?Money
    {
        return $this->money;
    }

    public function setMoney(?Money $money): static
    {
        $this->money = $money;

        return $this;
    }

    public function getItemPrefab(): ?ItemPrefab
    {
        return $this->itemPrefab;
    }

    public function setItemPrefab(?ItemPrefab $itemPrefab): static
    {
        $this->itemPrefab = $itemPrefab;

        return $this;
    }
}
