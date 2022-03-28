<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create an user
        $user = new User();
        // Set a password
        $password = 'password';

        $date = new DateTimeImmutable('now');

        $user->setUsername('toto');
        $user->setEmail('test@test.com');
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_USER']);
        $user->setPoints(10);
        $user->setCreatedAt($date);
        $user->setUpdatedAt($date);

        $manager->persist($user);

        $manager->flush();
    }
}
