<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LevelRepository::class)
 */
class Level
{//Entity class App\Entity\Level is final which can cause problems with proxies
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="float")
     */
    private float $maxPoints;//private ?float $maxPoints = null;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * //@Assert\Url(message="Le fichier est introuvable à l'emplacement spécifié")
     *
     * //@Assert\Url(message="Le chemin spécifié est invalide")
     *
     * @Assert\File(notFoundMessage="Le fichier est introuvable à l'emplacement spécifié")
     */
    private string $pathImg;//private ?string $pathImg = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $nomLevel;//private ?string $nomLevel = null;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="level")
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var ArrayCollection<User> $idUser
     */
    private $idUser;

    /**
     * Constructeur (méthode magique) qui définit
     * une collection d'utilisateurs
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
     * Fonction qui permet de récupérer
     * le chemin de l'image associée à un niveau
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * Fonction qui permet de définir
     * le chemin de l'image associée à un niveau
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
     * Fonction qui permet la suppression
     * de l'identifiant d'un utilisateur
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
