<?php

declare(strict_types=1);

namespace App\Test;

//Use statement cannot start with a backslash.
use AcceptanceTester;//use AcceptanceTester;

final class SigninCest
{
    public function loginSuccess(AcceptanceTester $I): void
    {
        $I->amOnPage('/login');
        $I->see('Welcome, Guest');
        $I->fillField('#email_address', 'sagarwal@claytonkendall.com');
        $I->fillField('#password', 'Test@123');
        $I->click('Login');
    }

    public function _before(AcceptanceTester $I): void
    {
        //_before()
        $I->amOnPage('/');
    }
}
