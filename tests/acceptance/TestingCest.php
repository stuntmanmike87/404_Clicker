<?php

declare(strict_types=1);

namespace App\Test;

//Use statement cannot start with a backslash.
use AcceptanceTester;//use AcceptanceTester;

//Use AcceptanceTester is from the same namespace – that is prohibited.
final class TestingCest
{
    //private $cookie = null;

    public function login(AcceptanceTester $I): void
    {
        $I->wantTo('welcome');
        $I->amOnPage('/');
        $I->seeInCurrentUrl('/login');
        $I->fillField('user', 'user_one');
        $I->fillField('pass', 'pass-pass');
        $I->click('Login');
        $I->see('You\'re logged!');
        //$this->cookie = $I->grabCookie('your-session-cookie-name');
    }

    public function _before(AcceptanceTester $I): void
    {
        //_before()
        $I->amOnPage('/');
    }

    public function _after(AcceptanceTester $I): void
    {
        //_after()
        $I->amOnPage('/');
    }
}
