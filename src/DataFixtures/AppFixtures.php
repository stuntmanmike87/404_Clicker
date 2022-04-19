<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Classe héritée de Fixture, qui gère les fixtures d'un objet de fixture
 */
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Fonction qui permet de charger des fixtures en persistant des objets [user (utilisateur)] en base de données
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Create an user
        $user1 = new User();
        // Set a password
        $password = 'password';

        $user1->setUsername('apprenti');
        $user1->setEmail('apprenti@apprenti.com');
        $user1->setPassword($this->hasher->hashPassword($user1, $password));
        $user1->setRoles(['ROLE_USER']);
        $user1->setPoints(10);

        $manager->persist($user1);

        // Create an user
        $user2 = new User();
        // Set a password
        $password = 'password';

        $user2->setUsername('junior');
        $user2->setEmail('junior@junior.com');
        $user2->setPassword($this->hasher->hashPassword($user2, $password));
        $user2->setRoles(['ROLE_USER']);
        $user2->setPoints(20);

        $manager->persist($user2);

        // Create an user
        $user3 = new User();
        // Set a password
        $password = 'password';

        $user3->setUsername('senior');
        $user3->setEmail('senior@senior.com');
        $user3->setPassword($this->hasher->hashPassword($user3, $password));
        $user3->setRoles(['ROLE_USER']);
        $user3->setPoints(50);

        $manager->persist($user3);

        $manager->flush();
    }
}
