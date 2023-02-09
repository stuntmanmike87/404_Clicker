<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use Error;
//use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
//use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserEntityTest extends KernelTestCase
{//final class UserEntityTest extends TestCase
    private const EMAIL_CONSTRAINT_MESSAGE = 'L\'e-mail {{value}} n\'est pas valide.';

    private const NOT_BLANK_CONSTRAINT_MESSAGE = 'Veuillez saisir une valeur.';

    private const INVALID_EMAIL_VALUE = 'user01@test';

    private const PASSWORD_REGEX_CONSTRAINT_MESSAGE = 'Le mot de passe doit être composé de 12 caractères dont au minimum : 1 lettre minuscule, 1 lettre majuscule, 1 chiffre, 1 caractère spécial (dans un ordre aléatoire).';

    private const VALID_EMAIL_VALUE = 'user01@test.com';

    private const VALID_PASSWORD_VALUE = 'User-nbr-001';

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Fonction d'initialisation du validateur de l'entité
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        //$this->validator = $kernel->getContainer()->get('validator');
        /** @var object|null $o */
        $o = $kernel->getContainer()->get('validator');
        /** @var ValidatorInterface $o */
        $this->validator = $o;
    }

    /**
     * Fonction qui teste la validité d'un utilisateur
     * de par la saisie d'un e-mail et d'un mot de passe
     * respectant les contraintes définies
     */
    public function testUserEntityIsValid(): void
    {
        /** @var User $user */
        $user = new User();

        $user->setEmail(self::VALID_EMAIL_VALUE);
        $user->setPassword(self::VALID_PASSWORD_VALUE);

        $errors = $this->getValidationErrors($user, 0);

        /** @var Error $error_0 */
        $error_0 = $errors[0];
        $error_0->getMessage();
    }

    /**
     * Fonction qui teste la validité d'un utilisateur
     * par la saisie d'un e-mail non vide
     */
    public function testUserEntityIsInvalidBecauseNoEmailEntered(): void
    {
        /** @var User $user */
        $user = new User();

        $user->setPassword(self::VALID_PASSWORD_VALUE);

        $errors = $this->getValidationErrors($user, 1);

        /** @var Error $error_0 */
        $error_0 = $errors[0];
        /** @var string $error0 */
        $error0 = $error_0->getMessage();

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $error0);
    }

    /**
     * Fonction qui teste la validité d'un utilisateur
     * par la saisie d'un mot de passe non vide
     */
    public function testUserEntityIsInvalidBecauseNoPasswordEntered(): void
    {
        /** @var User $user */
        $user = new User();

        $user->setEmail(self::VALID_EMAIL_VALUE);

        $errors = $this->getValidationErrors($user, 1);

        /** @var Error $error_0 */
        $error_0 = $errors[0];
        /** @var string $error0 */
        $error0 = $error_0->getMessage();

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $error0);
    }

    /**
     * Fonction qui teste la validité d'un utilisateur
     * par la saisie d'un e-mail respectant ses contraintes
     */
    public function testUserEntityIsInvalidBecauseOfInvalidEmailEntered(): void
    {
        /** @var User $user */
        $user = new User();

        $user->setEmail(self::INVALID_EMAIL_VALUE);
        $user->setPassword(self::VALID_PASSWORD_VALUE);

        $errors = $this->getValidationErrors($user, 1);

        /** @var Error $error_0 */
        $error_0 = $errors[0];
        /** @var string $error0 */
        $error0 = $error_0->getMessage();

        $this->assertEquals(self::EMAIL_CONSTRAINT_MESSAGE, $error0);
    }

    /**
     * Fonction qui teste la validité d'un utilisateur
     * par la saisie d'un mot de passe respectant ses contraintes
     *
     * @dataProvider provideInvalidPasswords
     */
    public function testUserEntityIsInvalidBecauseOfInvalidPasswordEntered(
        string $invalidPassword
    ): void {
        /** @var User $user */
        $user = new User();

        $user->setEmail(self::VALID_EMAIL_VALUE);
        $user->setPassword($invalidPassword);

        $errors = $this->getValidationErrors($user, 1);

        /** @var Error $error_0 */
        $error_0 = $errors[0];
        /** @var string $error0 */
        $error0 = $error_0->getMessage();

        $this->assertEquals(self::PASSWORD_REGEX_CONSTRAINT_MESSAGE, $error0);
    }

    /**
     * Fonction qui retourne un tableau de mots de passe
     * ne respectant pas une ou plusieurs contraintes
     *
     * @return array<array<string>>
     */
    public function provideInvalidPasswords(): array
    {
        return [
            ['Monemaildu974'],//no special character
            ['Monemail-le-clown'],//no numbers
            ['Monemail-97'],//not 12 characters
            ['monemail-du-974'],//no uppercase
            ['MONEMAIL-DU-974'],//no lowercase
        ];
    }

    /**
     * Fonction qui retourne une liste des erreurs
     * de violation des contraintes
     */
    private function getValidationErrors(
        User $user,
        int $numberOfExpectedErrors
    ): ConstraintViolationListInterface {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberOfExpectedErrors, $errors, '');

        return $errors;
    }
}
//Deprecation notices :
//15x: Since symfony/framework-bundle 5.2:
//Accessing the "validator" service directly from the container is deprecated,
//use dependency injection instead.
//9x in UserEntityTest::setUp from App\Tests
