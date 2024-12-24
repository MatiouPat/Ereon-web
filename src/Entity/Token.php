<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\TokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(output: false),
        new Patch(output: false),
        new Delete()
    ],
    normalizationContext: ['groups' => ['token:read']],
    paginationEnabled: false
)]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["map:read", "token:read", "world:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["map:read", "token:read", "world:read"])]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups(["map:read", "token:read","world:read"])]
    private ?int $height = null;

    #[ORM\Column]
    #[Groups(["map:read", "token:read", "world:read"])]
    private ?int $topPosition = null;

    #[ORM\Column]
    #[Groups(["map:read", "token:read", "world:read"])]
    private ?int $leftPosition = null;

    #[ORM\Column]
    #[Groups(["map:read", "token:read", "world:read"])]
    private ?int $zIndex = null;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'You must be 1(Token) 2(Map) and 3(GM) layer',
        min: 1,
        max: 3
    )]
    #[Groups(["map:read", "token:read", "world:read"])]
    private ?int $layer = null;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    #[Groups(["map:read", "token:read", "world:read"])]
    private ?Asset $asset = null;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Map $map = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'tokens')]
    #[Groups(["map:read", "token:read", "world:read"])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getTopPosition(): ?int
    {
        return $this->topPosition;
    }

    public function setTopPosition(int $topPosition): self
    {
        $this->topPosition = $topPosition;

        return $this;
    }

    public function getLeftPosition(): ?int
    {
        return $this->leftPosition;
    }

    public function setLeftPosition(int $leftPosition): self
    {
        $this->leftPosition = $leftPosition;

        return $this;
    }

    public function getZIndex(): ?int
    {
        return $this->zIndex;
    }

    public function setZIndex(int $zIndex): self
    {
        $this->zIndex = $zIndex;

        return $this;
    }

    public function getLayer(): ?int
    {
        return $this->layer;
    }

    public function setLayer(int $layer): self
    {
        $this->layer = $layer;

        return $this;
    }

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }

    public function getMap(): ?Map
    {
        return $this->map;
    }

    public function setMap(?Map $map): self
    {
        $this->map = $map;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
