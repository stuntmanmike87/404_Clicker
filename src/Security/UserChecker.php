<?php

declare(strict_types=1);

namespace App\Security;

//use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserChecker implements UserCheckerInterface
{
    /**
     * Fonction de vérification d'un utilisateur authentifié
     * dont le compte a été supprimé
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (! $user instanceof \App\Entity\User) {
            return;
        }

        if ((bool) $user->getIsDeleted()) {
            //the message passed to this exception
            //is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException(
                'Your user account no longer exists.'
            );
        }
    }

    /**
     * Fonction de vérification d'un utilisateur
     * authentifié dont le compte a expiré
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (! $user instanceof \App\Entity\User) {
            return;
        }

        //user account is expired, the user may be notified
        if ((bool) $user->getIsExpired()) {
            throw new AccountExpiredException('Your account is expired');
        }
    }
}
