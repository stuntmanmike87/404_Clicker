<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @final
 * @see \App\Tests\Entity\LevelTest */
#[ORM\Entity(repositoryClass: LevelRepository::class)]
class Level
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private readonly int $id;

    #[ORM\Column(type: Types::FLOAT)]
    private float $maxPoints;//private ?float $maxPoints = null;

    #[Assert\File(notFoundMessage: "Le fichier est introuvable à l'emplacement spécifié")]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $pathImg;//private ?string $pathImg = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $nomLevel;//private ?string $nomLevel = null;

    /**
     * @var ArrayCollection<User> $idUser
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'level')]
    private ArrayCollection $idUser;

    /**
     * Constructeur (méthode magique) qui définit une collection d'utilisateurs
     * selon un tableau d'entiers idUser (identifiants)
     */
    public function __construct()
    {
        $this->idUser = new ArrayCollection();
    }

    /**
     * Fonction qui permet de récupérer l'identifiant d'un niveau
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Fonction qui permet de récupérer le score maximal d'un joueur
     */
    public function getMaxPoints(): ?float
    {
        return $this->maxPoints;
    }

    /**
     * Fonction qui permet de définir le score maximal d'un joueur
     */
    public function setMaxPoints(float $maxPoints): self
    {
        $this->maxPoints = $maxPoints;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le chemin de l'image associée à un niveau
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * Fonction qui permet de définir le chemin de l'image associée à un niveau
     */
    public function setPathImg(string $pathImg): self
    {
        $this->pathImg = $pathImg;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le nom d'un niveau
     */
    public function getNomLevel(): ?string
    {
        return $this->nomLevel;
    }

    /**
     * Fonction qui permet de définir le nom d'un niveau
     */
    public function setNomLevel(string $nomLevel): self
    {
        $this->nomLevel = $nomLevel;

        return $this;
    }

    /**
     * Fonction qui donne accès à une collection d'utilisateurs
     */
    public function getIdUser(): Collection
    {
        return $this->idUser;
    }

    /**
     * Fonction qui permet l'ajout de l'identifiant d'un utilisateur
     */
    public function addIdUser(User $idUser): self
    {
        if (! $this->idUser->contains($idUser)) {
            $this->idUser[] = $idUser;
            $idUser->setLevel($this);
        }

        return $this;
    }

    /**
     * Fonction qui permet la suppression de l'identifiant d'un utilisateur
     */
    public function removeIdUser(User $idUser): self
    {
        //set the owning side to null (unless already changed)
        if ($this->idUser->removeElement($idUser) && $idUser->getLevel() === $this) {
            $idUser->setLevel(null);
        }

        return $this;
    }
}
