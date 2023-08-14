<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\MusicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection,
        new Get()
    ],
    normalizationContext: ['groups' => ['music:read']],
)]
class Music
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("music:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["music:read", "musicPlayer:read"])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["music:read", "musicPlayer:read"])]
    private ?string $link = null;

    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'musics')]
    private Collection $playlists;

    #[ORM\OneToMany(mappedBy: 'currentMusic', targetEntity: MusicPlayer::class)]
    private Collection $musicPlayers;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->musicPlayers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->addMusic($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removeMusic($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, MusicPlayer>
     */
    public function getMusicPlayers(): Collection
    {
        return $this->musicPlayers;
    }

    public function addMusicPlayer(MusicPlayer $musicPlayer): self
    {
        if (!$this->musicPlayers->contains($musicPlayer)) {
            $this->musicPlayers->add($musicPlayer);
            $musicPlayer->setCurrentMusic($this);
        }

        return $this;
    }

    public function removeMusicPlayer(MusicPlayer $musicPlayer): self
    {
        if ($this->musicPlayers->removeElement($musicPlayer)) {
            // set the owning side to null (unless already changed)
            if ($musicPlayer->getCurrentMusic() === $this) {
                $musicPlayer->setCurrentMusic(null);
            }
        }

        return $this;
    }
}
