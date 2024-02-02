<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Override;
use App\Entity\Level;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
        //Fixtures constructor for entity User
    }

    /**
     * Fonction de chargement des fixtures en base de données
     */
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $this->generateUsers(10, $manager);

        $manager->flush();
    }

    /**
     * Fonction de liaison de dépendances entre fixtures
     */
    //@return array<string>
    #[Override]
    public function getDependencies(): array
    {
        return [
            LevelFixtures::class,
        ];
    }

    /**
     * generateUsers function
     */
    private function generateUsers(
        int $number,
        ObjectManager $manager
    ): void {
        for ($i = 0; $i < $number; ++$i) {
            $user = new User();
            $password = 'password';

            $user->setUsername('usertest'.$i);
            $user->setEmail('user'.$i.'@test.com');
            $user->setPassword(
                $this->hasher->hashPassword($user, $password)
            );
            $user->setRoles(['ROLE_USER']);
            $user->setPoints((float) random_int(0, 19));
            $user->setFullName('User Number '.$i);

            /** @var Level $level */
            $level = $this->getReference('conception');
            //get a reference to a LevelFixture
            $user->setLevel($level);

            $manager->persist($user);
        }
    }
}
