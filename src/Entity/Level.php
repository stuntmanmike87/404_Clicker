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

    public function __construct()
    {
        $this->idUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxPoints(): ?float
    {
        return $this->maxPoints;
    }

    public function setMaxPoints(float $maxPoints): self
    {
        $this->maxPoints = $maxPoints;

        return $this;
    }

    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    public function setPathImg(string $pathImg): self
    {
        $this->pathImg = $pathImg;

        return $this;
    }

    public function getNomLevel(): ?string
    {
        return $this->nomLevel;
    }

    public function setNomLevel(string $nomLevel): self
    {
        $this->nomLevel = $nomLevel;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdUser(): Collection
    {
        return $this->idUser;
    }

    public function addIdUser(User $idUser): self
    {
        if (!$this->idUser->contains($idUser)) {
            $this->idUser[] = $idUser;
            $idUser->setLevel($this);
        }

        return $this;
    }

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