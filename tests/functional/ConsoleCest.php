<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Command\AddOneUserCommand;
use App\Tests\FunctionalTester;

final class ConsoleCest
{
    public function runSymfonyConsoleCommand(FunctionalTester $I): void
    {
        // Call Symfony console without option
        $output = $I->runSymfonyConsoleCommand(
            AddOneUserCommand::getDefaultName()
        );

        $I->assertStringContainsString('Hello world!', $output);

        // Call Symfony console with short option
        $output = $I->runSymfonyConsoleCommand(
            AddOneUserCommand::getDefaultName(),
            ['-s' => true]
        );

        $I->assertStringContainsString('Bye world!', $output);

        // Call Symfony console with long option
        $output = $I->runSymfonyConsoleCommand(
            AddOneUserCommand::getDefaultName(),
            ['--something' => true]
        );

        $I->assertStringContainsString('Bye world!', $output);
    }
}
