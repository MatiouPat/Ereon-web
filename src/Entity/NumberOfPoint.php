<?php

namespace App\Entity;

use App\Repository\NumberOfPointRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NumberOfPointRepository::class)]
class NumberOfPoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $current = null;

    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $min = null;

    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $max = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfPoints')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["personage:read"])]
    private ?Point $point = null;

    #[ORM\ManyToOne(inversedBy: 'numberOfPoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Personage $personage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrent(): ?int
    {
        return $this->current;
    }

    public function setCurrent(int $current): self
    {
        $this->current = $current;

        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function setMin(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getPoint(): ?Point
    {
        return $this->point;
    }

    public function setPoint(?Point $point): self
    {
        $this->point = $point;

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
