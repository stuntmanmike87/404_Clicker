<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Constructeur (méthode magique) de la classe ResetPasswordRequest déclarée
     *
     * @param object $user
     * @param \DateTimeInterface $expiresAt
     * @param string $selector
     * @param string $hashedToken
     */
    public function __construct(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    /**
     * Méthode pour récupérer l'identifiant d'un utilisateur
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Méthode qui donne accès à un utilisateur: objet User
     *
     * @return object
     */
    public function getUser(): object
    {
        return $this->user;
    }
}