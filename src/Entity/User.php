<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenDateValid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="idUser")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExpired;

    /**
     * Méthode de récupération de l'identifiant d'un utilisateur
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Fonction qui permet de récupérer l'adresse e-mail de l'utilisateur
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Fonction qui permet de définir l'adresse e-mail de l'utilisateur
     *
     * @param string $email
     * @return self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Fonction qui permet de définir le rôle de l'utilisateur
     *
     * @param array $roles
     * @return self
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
     *
     * @param string $password
     * @return self
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Fonction qui permet de définir le pseudo de l'utilisateur
     *
     * @param string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le score de l'utilisateur
     *
     * @return float|null
     */
    public function getPoints(): ?float
    {
        return $this->points;
    }

    /**
     * Fonction qui permet de définir le score de l'utilisateur
     *
     * @param float|null $points
     * @return self
     */
    public function setPoints(?float $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer la date d'enregistrement de l'utilisateur
     *
     * @return \DateTimeImmutable|null
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
        $this->createdAt = new \DateTimeImmutable;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer la date de mise à jour de l'utilisateur
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable;

        return $this;
    }

    /**
     * Fonction de récupération du jeton de sécurité (token)
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Fonction pour définir le jeton de sécurité (token)
     *
     * @param string|null $token
     * @return self
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Fonction de récupération la date de validité 
     * du jeton de sécurité (token)
     *
     * @return \DateTimeInterface|null
     */
    public function getTokenDateValid(): ?\DateTimeInterface
    {
        return $this->tokenDateValid;
    }

    /**
     * Fonction pour définir la date de validité 
     * du jeton de sécurité (token)
     *
     * @param \DateTimeInterface|null $tokenDateValid
     * @return self
     */
    public function setTokenDateValid(?\DateTimeInterface $tokenDateValid): self
    {
        $this->tokenDateValid = $tokenDateValid;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le niveau atteint par l'utilisateur
     *
     * @return Level|null
     */
    public function getLevel(): ?Level
    {
        return $this->level;
    }

    /**
     * Fonction qui permet de définir le niveau auquel se situe l'utilisateur
     *
     * @param Level|null $level
     * @return self
     */
    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Fonction de test qui indique si l'adresse e-mail 
     * de l'utilisateur est vérifiée ou non
     *
     * @return boolean
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Fonction qui permet de connaître le statut de vérification 
     * de l'adresse e-mail de l'utilisateur
     *
     * @return boolean|null
     */
    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }
    
    /**
     * Fonction qui permet de définir le statut de vérification 
     * de l'adresse e-mail de l'utilisateur
     *
     * @param boolean $isVerified
     * @return self
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le nom complet de l'utilisateur
     *
     * @return string|null     * 
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * Fonction qui permet de définir le nom complet de l'utilisateur
     *
     * @param string $fullName     * 
     * @return self     * 
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Fonction: méthode getter du user checker: 
     * statut de l'utilisateur: supprimé/non supprimé
     *
     * @return boolean|null
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * Fonction: méthode setter du user checker: 
     * statut de l'utilisateur: supprimé/non supprimé
     *
     * @param boolean $isDeleted
     * @return self
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Fonction: méthode getter du user checker: 
     * statut de l'utilisateur: expiré/non expiré
     *
     * @return boolean|null
     */
    public function getIsExpired(): ?bool
    {
        return $this->isExpired;
    }

    /**
     * Fonction: méthode setter du user checker: 
     * statut de l'utilisateur: expiré/non expiré
     *
     * @param boolean $isExpired
     * @return self
     */
    public function setIsExpired(bool $isExpired): self
    {
        $this->isExpired = $isExpired;

        return $this;
    }
}
