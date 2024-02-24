<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name:"`user`")]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    operations: [
        new GetCollection(),
        new Post(processor: UserPasswordHasher::class),
        new Patch(processor: UserPasswordHasher::class)
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['discordIdentifier' => 'exact', 'connections.world.serverIdentifier' => 'exact'])]
#[UniqueEntity(fields: ['username'], message: 'user.username.uniqueEntity')]
#[UniqueEntity(fields: ['email'], message: 'user.email.uniqueEntity')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user:read", "world:read", "map:read", "token:read", "connection:read", "personage:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[NotBlank]
    #[Groups(["user:read", "user:write", "world:read", "connection:read", "map:read", "dice:read"])]
    private ?string $username = null;
    
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups("user:write")]
    #[Length(min: '8', max: '4096', minMessage: 'user.plainPassword.length.min', maxMessage: 'user.plainPassword.length.max')]
    #[Regex(pattern: '^(?=.*\d)(?=.*[a-z]*)(?=.*[A-Z])(?=.*[#~?!:=;.@$%\^&*\/+-]).{8,}^', message: 'user.plainPassword.regex')]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write", "dice:read", "world:read"])]
    private ?string $discordIdentifier = null;

    #[ORM\ManyToMany(targetEntity: Token::class, mappedBy: 'users')]
    private Collection $tokens;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Connection::class, orphanRemoval: true)]
    #[Groups("user:read")]
    private Collection $connections;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Personage::class)]
    #[Groups("user:read")]
    private Collection $personages;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    #[Groups("user:read", "user:write")]
    private ?UserParameter $userParameter = null;

    #[ORM\OneToMany(mappedBy: 'launcher', targetEntity: Dice::class, orphanRemoval: true)]
    private Collection $dices;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
        $this->connections = new ArrayCollection();
        $this->personages = new ArrayCollection();
        $this->dices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDiscordIdentifier(): ?string
    {
        return $this->discordIdentifier;
    }

    public function setDiscordIdentifier(string $discordIdentifier): self
    {
        $this->discordIdentifier = $discordIdentifier;

        return $this;
    }

    /**
     * @return Collection<int, Token>
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function addToken(Token $token): self
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens->add($token);
            $token->addUser($this);
        }

        return $this;
    }

    public function removeToken(Token $token): self
    {
        if ($this->tokens->removeElement($token)) {
            $token->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Connection>
     */
    public function getConnections(): Collection
    {
        return $this->connections;
    }

    public function addConnection(Connection $connection): self
    {
        if (!$this->connections->contains($connection)) {
            $this->connections->add($connection);
            $connection->setUser($this);
        }

        return $this;
    }

    public function removeConnection(Connection $connection): self
    {
        if ($this->connections->removeElement($connection)) {
            // set the owning side to null (unless already changed)
            if ($connection->getUser() === $this) {
                $connection->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Personage>
     */
    public function getPersonages(): Collection
    {
        return $this->personages;
    }

    public function addPersonage(Personage $personage): self
    {
        if (!$this->personages->contains($personage)) {
            $this->personages->add($personage);
            $personage->setUser($this);
        }

        return $this;
    }

    public function removePersonage(Personage $personage): self
    {
        if ($this->personages->removeElement($personage)) {
            // set the owning side to null (unless already changed)
            if ($personage->getUser() === $this) {
                $personage->setUser(null);
            }
        }

        return $this;
    }

    public function getUserParameter(): ?UserParameter
    {
        return $this->userParameter;
    }

    public function setUserParameter(UserParameter $userParameter): self
    {
        // set the owning side of the relation if necessary
        if ($userParameter->getUser() !== $this) {
            $userParameter->setUser($this);
        }

        $this->userParameter = $userParameter;

        return $this;
    }

    /**
     * @return Collection<int, Dice>
     */
    public function getDices(): Collection
    {
        return $this->dices;
    }

    public function addDice(Dice $dice): static
    {
        if (!$this->dices->contains($dice)) {
            $this->dices->add($dice);
            $dice->setLauncher($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): static
    {
        if ($this->dices->removeElement($dice)) {
            // set the owning side to null (unless already changed)
            if ($dice->getLauncher() === $this) {
                $dice->setLauncher(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
