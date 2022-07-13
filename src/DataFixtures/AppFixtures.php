<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Level;
use App\Entity\User;
//use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Classe héritée de Fixture, qui gère les fixtures d'un objet de fixture
 */
final class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    /**
     * Fonction qui permet de charger des fixtures
     * en persistant des objets [user (utilisateur)] en base de données
     */
    public function load(ObjectManager $manager): void
    {
        //UserFactory::new()->create();
        //UserFactory::new()->createMany(10);

        $this->loadUsers($manager);

        $manager->flush();
    }

    /**
     * loadUsers function
     */
    private function loadUsers(ObjectManager $manager): void
    {
        foreach (
            $this->getUserData() as [
                $email,
                $roles,
                $password,
                $username,
                $points,
                $fullName,
            ]
        ) {
            $user = new User();

            $user->setEmail(strval($email));
            $user->setRoles([strval($roles)]);
            $user->setPassword(
                $this->hasher->hashPassword($user, strval($password))
            );
            $user->setUsername(strval($username));
            $user->setPoints(floatval($points));
            $user->setFullName(strval($fullName));

            $manager->persist($user);

            /** @var Level $level */
            $level = $this->getReference('conception');
            //get a reference to a LevelFixture
            $user->setLevel($level);
        }
    }

    /**
     * getUserData function
     *
     * @return array<int, array<int, array<int, string>|string>>
     */
    private function getUserData(): array
    {
        return [//$userData = [];
            [
                'theo@myemail.com',
                ['ROLE_USER'],
                'password',
                'apprenti',
                '10',
                'Théo Apprend',
            ],
            [
                'junior@lolgamers.com',
                ['ROLE_USER'],
                'password',
                'junior',
                '20',
                'Luc Junior',
            ],
            [
                'lucas@lolgamers.com',
                ['ROLE_USER'],
                'password',
                'senior',
                '50',
                'Lucas Senior',
            ],
        ];
    }

    /**
     * Fonction de liaison de dépendances avec une autre fixture
     *
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            LevelFixtures::class,
        ];
    }
}
