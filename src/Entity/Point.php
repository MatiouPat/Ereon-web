<?php

namespace App\Entity;

use App\Repository\PointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PointRepository::class)]
class Point
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["personage:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    #[Groups(["personage:read"])]
    private ?string $acronym = null;

    #[ORM\ManyToOne(inversedBy: 'points')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\OneToMany(mappedBy: 'point', targetEntity: NumberOfPoint::class, orphanRemoval: true)]
    private Collection $numberOfPoints;

    public function __construct()
    {
        $this->numberOfPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): self
    {
        $this->acronym = $acronym;

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
     * @return Collection<int, NumberOfPoint>
     */
    public function getNumberOfPoints(): Collection
    {
        return $this->numberOfPoints;
    }

    public function addNumberOfPoint(NumberOfPoint $numberOfPoint): self
    {
        if (!$this->numberOfPoints->contains($numberOfPoint)) {
            $this->numberOfPoints->add($numberOfPoint);
            $numberOfPoint->setPoint($this);
        }

        return $this;
    }

    public function removeNumberOfPoint(NumberOfPoint $numberOfPoint): self
    {
        if ($this->numberOfPoints->removeElement($numberOfPoint)) {
            // set the owning side to null (unless already changed)
            if ($numberOfPoint->getPoint() === $this) {
                $numberOfPoint->setPoint(null);
            }
        }

        return $this;
    }
}