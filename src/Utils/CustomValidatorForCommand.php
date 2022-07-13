<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

final class CustomValidatorForCommand
{
    /**
     * Validates an email entered by the user in CLI.
     */
    public function validateEmail(?string $emailEntered): string
    {
        if (empty($emailEntered)) {//Use of empty() is disallowed.
            throw new InvalidArgumentException('VEUILLEZ SAISIR UN E-MAIL.');
        }

        if (! filter_var($emailEntered, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('E-MAIL SAISI INVALIDE.');
        }

        [, $domain] = explode('@', $emailEntered);

        if (! checkdnsrr($domain)) {
            throw new InvalidArgumentException('E-MAIL SAISI INVALIDE.');
        }

        return $emailEntered;
    }

    /**
     * Validates a password entered by the user in CLI.
     */
    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {//Use of empty() is disallowed.
            throw new InvalidArgumentException(
                'VEUILLEZ SAISIR UN MOT DE PASSE.'
            );
        }

        $passwordRegex =
            '/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*\d)(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$/';

        if (! preg_match($passwordRegex, $plainPassword)) {
            throw new InvalidArgumentException(
                "LE MOT DE PASSE DOIT CONTENIR 12 CARACTERES 
                AU MINIMUM DANS UN ORDRE ALEATOIRE DONT: 
                1 LETTRE MINUSCULE? 1 LETTRE MAJUSCULE, 
                1 CHIFFRE, ET 1 CARACTERE SPECIAL."
            );
        }

        return $plainPassword;
    }

    /**
     * Checks if an user's email (entered by the user) is stored in database.
     */
    public function checkEmailForUserDelete(?string $emailEntered): string
    {
        if (empty($emailEntered)) {//Use of empty() is disallowed.
            throw new InvalidArgumentException("VEUILLEZ SAISIR UN E-MAIL.");
        }

        if (! filter_var($emailEntered, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("E-MAIL SAISI INVALIDE.");
        }

        return $emailEntered;
    }
}
