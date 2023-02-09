<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProfileControllerTest extends WebTestCase
{
    //https://symfony.com/doc/5.4/testing.html#submitting-forms
    //https://symfony.com/doc/5.4/testing.html#testing-the-response-assertions

    /**
     * Fonction de test de connexion d'utilisateurs
     */
    public function testVisitingWhileLoggedIn(): void
    {//Useless late static binding because class is final
        $client = WebTestCase::createClient();
        //$client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = WebTestCase::getContainer()
            ->get(UserRepository::class);
        //$userRepository = static::getContainer()
        //->get(UserRepository::class);

        // retrieve the test user
        /** @var User $testUser */
        $testUser = $userRepository->findOneBy([
            'email' => 'john.doe@example.com',
        ]);//findOneByEmail('john.doe@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/profile');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello John!');
    }
}
