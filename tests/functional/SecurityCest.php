<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;
use App\Tests\FunctionalTester;

final class SecurityCest
{
    public function dontSeeAuthentication(FunctionalTester $I): void
    {
        $I->amOnPage('/dashboard');
        $I->dontSeeAuthentication();
    }

    public function dontSeeRememberedAuthentication(FunctionalTester $I): void
    {
        $I->amOnPage('/login');
        $I->submitForm(
            'form[name=login]',
            [
                'email' => 'john_doe@gmail.com',
                'password' => '123456',
                //'_remember_me' => false,
            ]
        );

        $I->dontSeeRememberedAuthentication();
    }

    public function seeAuthentication(FunctionalTester $I): void
    {
        $user = $I->grabEntityFromRepository(
            User::class,
            [
                'email' => 'john_doe@gmail.com',
            ]
        );

        $I->amLoggedInAs($user);
        $I->amOnPage('/dashboard');

        $I->seeAuthentication();
    }

    public function seeRememberedAuthentication(FunctionalTester $I): void
    {
        $I->amOnPage('/login');
        $I->submitForm(
            'form[name=login]',
            [
                'email' => 'john_doe@gmail.com',
                'password' => '123456',
                //'_remember_me' => true,
            ]
        );

        $I->seeRememberedAuthentication();
    }

    public function seeUserHasRole(FunctionalTester $I): void
    {
        $user = $I->grabEntityFromRepository(
            User::class,
            [
                'email' => 'john_doe@gmail.com',
            ]
        );

        $I->amLoggedInAs($user);
        $I->amOnPage('/');

        $I->seeUserHasRole('ROLE_USER');
    }

    public function seeUserHasRoles(FunctionalTester $I): void
    {
        $user = $I->grabEntityFromRepository(
            User::class,
            [
                'email' => 'john_doe@gmail.com',
            ]
        );

        $I->amLoggedInAs($user);
        $I->amOnPage('/');

        $I->seeUserHasRoles(['ROLE_USER', 'ROLE_CUSTOMER']);
    }

    public function seeUserPasswordDoesNotNeedRehash(FunctionalTester $I): void
    {
        $user = $I->grabEntityFromRepository(
            User::class,
            [
                'email' => 'john_doe@gmail.com',
            ]
        );

        $I->amLoggedInAs($user);
        $I->amOnPage('/dashboard');

        $I->seeUserPasswordDoesNotNeedRehash();
    }
}
