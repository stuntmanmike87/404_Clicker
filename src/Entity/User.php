<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
//use Doctrine\ORM\Mapping\Table;
//use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @ORM\HasLifecycleCallbacks
 *
 * //@Table(name="users")
 *
 * //@UniqueEntity(fields={"email"},message="Impossible de créer un compte utilisateur avec cet e-mail.")
 *///Normal classes are forbidden. Classes must be final or abstract
class User implements UserInterface, PasswordAuthenticatedUserInterface
{//Entity class App\Entity\User is final which can cause problems with proxies
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var int $id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     *
     * @Assert\Email(message="L'e-mail {{value}} n'est pas valide.")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string $email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var array<string> $roles
     */
    private $roles = [];

    /**
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     *
     * @Assert\NotCompromisedPassword(message="Ce mot de passe a été divulgué lors d'une fuite de données, veuillez utiliser un autre mot de passe pour votre sécurité.")
     *
     * @Assert\Regex(pattern="/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$/", message="Le mot de passe doit être composé de 12 caractères dont au minimum : 1 lettre minuscule, 1 lettre majuscule, 1 chiffre, 1 caractère spécial (dans un ordre aléatoire).")
     *
     * @ORM\Column(type="string")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string $password : The hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string $username
     */
    private $username;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var float|null $points
     */
    private $points;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var \DateTimeImmutable $createdAt
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var \DateTimeImmutable $updatedAt
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string|null $token
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var \DateTimeInterface|null $tokenDateValid
     */
    private $tokenDateValid;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="idUser")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var Level|null $level
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string $fullName
     */
    private $fullName;

    /**
     * @ORM\Column(type="boolean")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var bool $isVerified
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var bool $isDeleted
     */
    private $isDeleted = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var bool $isExpired
     */
    private $isExpired = false;

    /**
     * Méthode de récupération de l'identifiant d'un utilisateur
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Fonction qui permet de récupérer l'adresse e-mail de l'utilisateur
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Fonction qui permet de définir l'adresse e-mail de l'utilisateur
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Fonction qui permet de récupérer le pseudo de l'utilisateur
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        //guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Fonction qui permet de définir le rôle de l'utilisateur
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Fonction qui permet de définir le mot de passe de l'utilisateur
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
     * Fonction pour effacer des données sensibles ou temporaires
     * sur l'utilisateur, i.e. un mot de passe en clair
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        //If you store any temporary, sensitive data
        //on the user, clear it here
        //$this->plainPassword = null;
    }

    /**
     * Fonction qui permet de définir le pseudo de l'utilisateur
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le score de l'utilisateur
     */
    public function getPoints(): ?float
    {
        return $this->points;
    }

    /**
     * Fonction qui permet de définir le score de l'utilisateur
     */
    public function setPoints(?float $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer
     * la date d'enregistrement de l'utilisateur
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Fonction qui permet de récupérer
     * la date de mise à jour de l'utilisateur
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Fonction de récupération du jeton de sécurité (token)
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Fonction pour définir le jeton de sécurité (token)
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Fonction de récupération la date de validité
     * du jeton de sécurité (token)
     */
    public function getTokenDateValid(): ?\DateTimeInterface
    {
        return $this->tokenDateValid;
    }

    /**
     * Fonction pour définir la date de validité
     * du jeton de sécurité (token)
     */
    public function setTokenDateValid(?\DateTimeInterface $tokenDateValid): self
    {
        $this->tokenDateValid = $tokenDateValid;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le niveau atteint par l'utilisateur
     */
    public function getLevel(): ?Level
    {
        return $this->level;
    }

    /**
     * Fonction qui permet de définir le niveau auquel se situe l'utilisateur
     */
    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Fonction qui permet de connaître le statut de vérification
     * de l'adresse e-mail de l'utilisateur
     */
    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * Fonction qui permet de définir le statut de vérification
     * de l'adresse e-mail de l'utilisateur
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le nom complet de l'utilisateur
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * Fonction qui permet de définir le nom complet de l'utilisateur
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Fonction: méthode getter du user checker:
     * statut de l'utilisateur: supprimé/non supprimé
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * Fonction: méthode setter du user checker:
     * statut de l'utilisateur: supprimé/non supprimé
     */
    public function setIsDeleted(bool $isDeleted): self
    {

        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Fonction: méthode getter du user checker:
     * statut de l'utilisateur: expiré/non expiré
     */
    public function getIsExpired(): ?bool
    {
        return $this->isExpired;
    }

    /**
     * Fonction: méthode setter du user checker:
     * statut de l'utilisateur: expiré/non expiré
     */
    public function setIsExpired(bool $isExpired): self
    {

        $this->isExpired = $isExpired;

        return $this;
    }
}
