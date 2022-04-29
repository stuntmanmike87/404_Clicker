<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Classe héritée de Fixture, qui gère les fixtures d'un objet de fixture
 */
class AppFixtures extends Fixture implements DependentFixtureInterface
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
        $user1 = new User();// Create an user
        $password = 'password';// Set a password

        $user1->setUsername('apprenti');
        $user1->setEmail('apprenti@apprenti.com');
        $user1->setPassword($this->hasher->hashPassword($user1, $password));
        $user1->setRoles(['ROLE_USER']);
        $user1->setPoints(10);
        $user1->setFullName('Théo Apprend');
        $user1->setMessage('Bonjour Team LAG, Théo');

        $user1->setLevel($this->getReference('conception'));//get a reference to a LevelFixture

        $manager->persist($user1);


        $user2 = new User();
        $password = 'password';

        $user2->setUsername('junior');
        $user2->setEmail('junior@junior.com');
        $user2->setPassword($this->hasher->hashPassword($user2, $password));
        $user2->setRoles(['ROLE_USER']);
        $user2->setPoints(20);
        $user2->setFullName('Luc Junior');
        $user2->setMessage('Hello la Team :)');

        $user2->setLevel($this->getReference('developpement'));

        $manager->persist($user2);


        $user3 = new User();
        $password = 'password';

        $user3->setUsername('senior');
        $user3->setEmail('senior@senior.com');
        $user3->setPassword($this->hasher->hashPassword($user3, $password));
        $user3->setRoles(['ROLE_USER']);
        $user3->setPoints(50);
        $user1->setFullName('Lucas Senior');
        $user1->setMessage('');

        $user3->setLevel($this->getReference('production'));

        $manager->persist($user3);


        $manager->flush();
    }

    public function getDependencies()
   {
      return [
         LevelFixtures::class  
      ];
   }

}
