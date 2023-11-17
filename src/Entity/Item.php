<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ApiResource(
    operations: [
        new Post
    ]
)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Personage $personage = null;

    #[ORM\ManyToOne(inversedBy: 'items', cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read", 'personage:write'])]
    private ?ItemPrefab $itemPrefab = null;

    public function getId(): ?int
    {
        return $this->id;
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
