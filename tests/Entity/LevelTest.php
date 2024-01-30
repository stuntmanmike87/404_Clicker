<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use Iterator;
use App\Entity\Level;
//use App\Entity\User;
use Error;
//use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
//use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class LevelTest extends KernelTestCase
{//final class LevelTest extends TestCase
    /* public function __construct(
        $maxPoints, $pathImg,
        $nomLevel,
        $idUser
    ) {
        $this->maxPoints = $maxPoints;
        $this->pathImg = $pathImg;
        $this->nomLevel = $nomLevel;
        $this->idUser = $idUser;
    }

    public function testLevelFieldsValidity()
    {
        $level = new LevelTest(
            10,
            '/assets/images/level2_dev.png',
            'developpement', []
        );
    } */

    ///** @var string */
    //private const VALID_DIRECTORY_PATH_VALUE = '/assets/images/';
    ///** @var array<string> */
    /* private const VALID_IMG_FILE_NAMES = [
        'level1_concept.png',
        'level2_dev.png',
        'level3_prod.png',
        '404.png','500.webp',
        '403.png'
    ]; */

    private const string VALID_PATHIMG = '/assets/images/level1_concept.png';

    private const string PATH_CONSTRAINT_MESSAGE = 'Le fichier est introuvable à l\'emplacement spécifié';

    //private const INVALID_DIRECTORY_PATH_MESSAGE = 'Le chemin spécifié est invalide';

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var ValidatorInterface $validator
     */
    private $validator;

    /**
     * Fonction d'initialisation du validateur de l'entité
     */
    #[Override]
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
     * Fonction qui teste la validité d'un niveau
     * selon un chemin existant
     */
    public function testLevelIsValid(): void
    {
        /** @var Level $level */
        $level = new Level();

        $level->setPathImg(self::VALID_PATHIMG);

        $errors = $this->getValidationErrors($level, 1);

        /** @var Error $error_0 */
        $error_0 = $errors[0];

        $error_0->getMessage();
    }

    /**
     * Fonction qui teste la validité d'un niveau selon
     * le chemin de l'image qui lui est associée
     */
    #[DataProvider('provideInvalidPaths')]
    public function testLevelIsInvalidBecauseInvalidPathIsDefined(
        string $invalidPath
    ): void {
        /** @var Level $level */
        $level = new Level();

        $level->setPathImg($invalidPath);

        $errors = $this->getValidationErrors($level, 1);

        /** @var Error $error_0 */
        $error_0 = $errors[0];

        /** @var string $error0 */
        $error0 = $error_0->getMessage();

        $this->assertSame(self::PATH_CONSTRAINT_MESSAGE, $error0);
        //$this->assertEquals(
        //self::PATH_CONSTRAINT_MESSAGE,
        //$errors[0]->getMessage()
        //);
        //$this->assertFileExists(
        //self::PATH_CONSTRAINT_MESSAGE,
        //$errors[0]->getMessage()
        //);
    }

    /**
     * Fonction qui retourne un tableau de chemins invalides
     */
    public static function provideInvalidPaths(): Iterator
    {
        //this file can't be found at this location
        yield ['/assets/images/level1_concept'];
        // no file extension
        yield ['/assets/images/level1_concept.txt'];
        // wrong file extension
        yield ['/assets/images/level1.png'];
        // wrong file name
        yield ['/assets/image/level1_concept.png'];
        // no such folder
        yield ['/assets/images/'];
    }

    /**
     * Fonction qui retourne une liste des erreurs
     * de violation des contraintes
     */
    private function getValidationErrors(
        Level $level,
        int $numberOfExpectedErrors
    ): ConstraintViolationListInterface {
        $errors = $this->validator->validate($level);

        $this->assertCount($numberOfExpectedErrors, $errors, '');

        return $errors;
    }
}

//Deprecation notices :
//Since symfony/framework-bundle 5.2:
//Accessing the "validator" service directly from the container is deprecated,
//use dependency injection instead.
//6x in LevelTest::setUp from App\Tests
