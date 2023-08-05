<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/** @final */
#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{//Entity class ... is final which can cause problems with proxies
    use ResetPasswordRequestTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private readonly int $id;
//Class App\Entity\ResetPasswordRequest has an uninitialized readonly property $id. Assign it in the constructor.
    /**
     * Constructeur (méthode magique) de la classe ResetPasswordRequest déclarée
     *
     * @param User $user
     */
    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        private readonly object $user,
        DateTimeInterface $expiresAt,
        string $selector,
        string $hashedToken
    ) {
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    /**
     * Méthode pour récupérer l'identifiant d'un utilisateur
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Méthode qui donne accès à un utilisateur: objet User
     */
    public function getUser(): object
    {
        return $this->user;
    }
}
