<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/** @final */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private readonly int $id;

    #[Assert\NotBlank(message: 'Veuillez saisir une valeur.')]
    #[Assert\Email(message: "L'e-mail {{value}} n'est pas valide.")]
    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private string $email; // private ?string $email = null;

    /**
     * @var array<string> $roles
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * @var string $password : The hashed password
     */
    #[Assert\NotBlank(message: 'Veuillez saisir une valeur.')]
    #[Assert\NotCompromisedPassword(message: "Ce mot de passe a été divulgué lors d'une fuite de données, veuillez utiliser un autre mot de passe pour votre sécurité.")]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*\d)(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$/',
        message: 'Le mot de passe doit être composé de 12 caractères dont au minimum : 1 lettre minuscule, 1 lettre majuscule, 1 chiffre, 1 caractère spécial (dans un ordre aléatoire).'
        )]
    #[ORM\Column(type: Types::STRING)]
    private string $password; // private ?string $password = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $username; // private ?string $username = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $points = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt; // private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $updatedAt; // private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $tokenDateValid = null;

    #[ORM\ManyToOne(targetEntity: Level::class, inversedBy: 'idUser')]
    private ?Level $level = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $fullName; // private ?string $fullName = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVerified = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isDeleted = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isExpired = false;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $resetToken = null;

    /**
     * Méthode de récupération de l'identifiant d'un utilisateur.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Fonction qui permet de récupérer l'adresse e-mail de l'utilisateur.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Fonction qui permet de définir l'adresse e-mail de l'utilisateur.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    #[\Override]
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Fonction qui permet de récupérer le pseudo de l'utilisateur.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    #[\Override]
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Fonction qui permet de définir le rôle de l'utilisateur.
     *
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    #[\Override]
    public function getPassword(): string// ?string
    {
        return $this->password;
    }

    /**
     * Fonction qui permet de définir le mot de passe de l'utilisateur.
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Fonction pour effacer des données sensibles ou temporaires sur l'utilisateur,
     * i.e. un mot de passe en clair.
     *
     * @see UserInterface
     */
    #[\Override]
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data
        // on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Fonction qui permet de définir le pseudo de l'utilisateur.
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le score de l'utilisateur.
     */
    public function getPoints(): ?float
    {
        return $this->points;
    }

    /**
     * Fonction qui permet de définir le score de l'utilisateur.
     */
    public function setPoints(?float $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer la date d'enregistrement de l'utilisateur.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Fonction qui permet de récupérer
     * la date de mise à jour de l'utilisateur.
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Fonction de récupération du jeton de sécurité (token).
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Fonction pour définir le jeton de sécurité (token).
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Fonction de récupération la date de validité du jeton de sécurité (token).
     */
    public function getTokenDateValid(): ?\DateTimeInterface
    {
        return $this->tokenDateValid;
    }

    /**
     * Fonction pour définir la date de validité du jeton de sécurité (token).
     */
    public function setTokenDateValid(?\DateTimeInterface $tokenDateValid): self
    {
        $this->tokenDateValid = $tokenDateValid;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le niveau atteint par l'utilisateur.
     */
    public function getLevel(): ?Level
    {
        return $this->level;
    }

    /**
     * Fonction qui permet de définir le niveau auquel se situe l'utilisateur.
     */
    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Fonction qui permet de connaître le statut de vérification de l'adresse e-mail de l'utilisateur.
     */
    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * Fonction qui permet de définir le statut de vérification de l'adresse e-mail de l'utilisateur.
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le nom complet de l'utilisateur.
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * Fonction qui permet de définir le nom complet de l'utilisateur.
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Fonction: méthode getter du user checker: statut de l'utilisateur: supprimé/non supprimé.
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * Fonction: méthode setter du user checker: statut de l'utilisateur: supprimé/non supprimé.
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Fonction: méthode getter du user checker: statut de l'utilisateur: expiré/non expiré.
     */
    public function getIsExpired(): ?bool
    {
        return $this->isExpired;
    }

    /**
     * Fonction: méthode setter du user checker: statut de l'utilisateur: expiré/non expiré.
     */
    public function setIsExpired(bool $isExpired): self
    {
        $this->isExpired = $isExpired;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
