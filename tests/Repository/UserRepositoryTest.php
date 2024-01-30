<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use Doctrine\ORM\EntityManager;
use Override;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class UserRepositoryTest extends KernelTestCase
{
    protected ?EntityManager $entityManager = null;

    #[Override]
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        // $this->entityManager = $kernel
        //     ->getContainer()
        //     ->get('doctrine')
        //     ->getManager();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $kernel
            ->getContainer()
            ->get('doctrine')
            //->getManager()
        ;
        $doctrine->getManager();
    }

    #[Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        //$this->entityManager = null;//->NULL;
    }

    public function testSearchByName(): void
    {
        // /** @var User $user */
        // $user = $this
        //     ->entityManager
        //     ->getRepository(User::class)
        //     ->findOneBy(['name' => 'Nameless'])
        // ;
        /** @var EntityManager $entityManager */
        $entityManager = $this->entityManager;

        /** @var UserRepository $repository */
        $repository = $entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $repository->findOneBy(['name' => 'Nameless']);

        $this->assertSame('nom', $user->getUsername());
    }
}
