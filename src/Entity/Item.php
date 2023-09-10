<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemInformation $itemInformation = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Personage $personage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemInformation(): ?ItemInformation
    {
        return $this->itemInformation;
    }

    public function setItemInformation(?ItemInformation $itemInformation): static
    {
        $this->itemInformation = $itemInformation;

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
}
