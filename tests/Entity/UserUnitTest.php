<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();

        $user->setEmail('true@test.com');
        $user->setPassword('password');
        $user->setUsername('username');

        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getUsername() === 'username');
    }

    public function testIsFalse(): void
    {
        $user = new User();

        $user->setEmail('true@test.com');
        $user->setPassword('password');
        $user->setUsername('username');

        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->getUsername() === 'false');
    }

    public function testIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getUsername());
    }
}
