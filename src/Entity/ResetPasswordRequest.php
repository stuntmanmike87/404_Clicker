<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{//Entity class App\Entity\ResetPasswordRequest is final which can cause problems with proxies
    use ResetPasswordRequestTrait;

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
     * @ORM\ManyToOne(targetEntity=User::class)
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var User $user
     */
    private $user;

    /**
     * Constructeur (méthode magique)
     * de la classe ResetPasswordRequest déclarée
     */
    public function __construct(
        object $user,
        \DateTimeInterface $expiresAt,
        string $selector,
        string $hashedToken
    )
    {
        /** @var User $user */
        $this->user = $user;
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
