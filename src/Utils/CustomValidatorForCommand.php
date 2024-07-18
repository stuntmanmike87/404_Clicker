<?php

declare(strict_types=1);

namespace App\Utils;

use Nette\Utils\Strings;
use Symfony\Component\Console\Exception\InvalidArgumentException;

final class CustomValidatorForCommand
{
    private const PW_CONSTRAINTS_MESSAGE = 'LE MOT DE PASSE DOIT CONTENIR'.PHP_EOL.
        '12 CARACTERES AU MINIMUM DANS UN ORDRE ALEATOIRE DONT: '.PHP_EOL.
        '1 LETTRE MINUSCULE? 1 LETTRE MAJUSCULE, 1 CHIFFRE, ET 1 CARACTERE SPECIAL.';

    /** Validates an email entered by the user in CLI. */
    public function validateEmail(?string $emailEntered): string
    {
        // if (empty($emailEntered)) {//Use of empty() is disallowed.
        if ('' === (string) $emailEntered) {
            throw new InvalidArgumentException('VEUILLEZ SAISIR UN E-MAIL.');
        }

        if (!(bool) filter_var($emailEntered, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('E-MAIL SAISI INVALIDE.');
        }

        [, $domain] = explode('@', (string) $emailEntered);

        if (!checkdnsrr($domain)) {
            throw new InvalidArgumentException('E-MAIL SAISI INVALIDE.');
        }

        return (string) $emailEntered;
    }

    /** Validates a password entered by the user in CLI. */
    public function validatePassword(?string $plainPassword): string
    {
        // if (empty($plainPassword)) {//Use of empty() is disallowed.
        if ('' === (string) $plainPassword) {
            throw new InvalidArgumentException('VEUILLEZ SAISIR UN MOT DE PASSE.');
        }

        $passwordRegex = '/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*\d)(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$/';

        $result = Strings::match((string) $plainPassword, $passwordRegex);
        // if (! preg_match($passwordRegex, $plainPassword)) {
        if ([] !== (array) $result) {
            throw new InvalidArgumentException(self::PW_CONSTRAINTS_MESSAGE);
        }

        return (string) $plainPassword;
    }

    /** Checks if an user's email (entered by the user) is stored in database. */
    public function checkEmailForUserDelete(?string $emailEntered): string
    {
        // if (empty($emailEntered)) {//Use of empty() is disallowed.
        if ('' === (string) $emailEntered) {
            throw new InvalidArgumentException('VEUILLEZ SAISIR UN E-MAIL.');
        }

        if (!(bool) filter_var($emailEntered, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('E-MAIL SAISI INVALIDE.');
        }

        return (string) $emailEntered;
    }
}
