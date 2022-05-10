<?php

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LevelRepository::class)
 */
class Level
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $maxPoints;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathImg;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomLevel;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="level")
     */
    private $idUser;

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
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Fonction qui permet de récupérer le score maximal d'un joueur
     * 
     */
    public function getMaxPoints(): ?float
    {
        return $this->maxPoints;
    }

    /**
     * Fonction qui permet de définir le score maximal d'un joueur
     *
     * @param float $maxPoints
     * @return self
     */
    public function setMaxPoints(float $maxPoints): self
    {
        $this->maxPoints = $maxPoints;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le chemin de l'image associée à un niveau
     *
     * @return string|null
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * Fonction qui permet de définir le chemin de l'image associée à un niveau
     *
     * @param string $pathImg
     * @return self
     */
    public function setPathImg(string $pathImg): self
    {
        $this->pathImg = $pathImg;

        return $this;
    }

    /**
     * Fonction qui permet de récupérer le nom d'un niveau
     *
     * @return string|null
     */
    public function getNomLevel(): ?string
    {
        return $this->nomLevel;
    }

    /**
     * Fonction qui permet de définir le nom d'un niveau
     *
     * @param string $nomLevel
     * @return self
     */
    public function setNomLevel(string $nomLevel): self
    {
        $this->nomLevel = $nomLevel;

        return $this;
    }

    /**
     * Fonction qui donne accès à une collection d'utilisateurs
     * 
     * @return Collection<int, User>
     */
    public function getIdUser(): Collection
    {
        return $this->idUser;
    }

    /**
     * Fonction qui permet l'ajout de l'identifiant d'un utilisateur
     *
     * @param User $idUser
     * @return self
     */
    public function addIdUser(User $idUser): self
    {
        if (!$this->idUser->contains($idUser)) {
            $this->idUser[] = $idUser;
            $idUser->setLevel($this);
        }

        return $this;
    }

    /**
     * Fonction qui permet la suppression de l'identifiant d'un utilisateur
     *
     * @param User $idUser
     * @return self
     */
    public function removeIdUser(User $idUser): self
    {
        if ($this->idUser->removeElement($idUser)) {
            // set the owning side to null (unless already changed)
            if ($idUser->getLevel() === $this) {
                $idUser->setLevel(null);
            }
        }

        return $this;
    }
}
