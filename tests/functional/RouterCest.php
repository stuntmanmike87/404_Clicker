<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

final class RouterCest
{
    public function amOnAction(FunctionalTester $I): void
    {
        $I->amOnAction('HomeController');
        $I->see('Hello World!');
    }

    public function amOnRoute(FunctionalTester $I): void
    {
        $I->amOnRoute('index');
        $I->see('Hello World!');
    }

    public function seeCurrentActionIs(FunctionalTester $I): void
    {
        $I->amOnPage('/');
        $I->seeCurrentActionIs('HomeController');
    }

    public function seeCurrentRouteIs(FunctionalTester $I): void
    {
        $I->amOnPage('/login');
        $I->seeCurrentRouteIs('app_login');
    }

    public function seeInCurrentRoute(FunctionalTester $I): void
    {
        $I->amOnPage('/');
        $I->seeInCurrentRoute('index');
    }
}
