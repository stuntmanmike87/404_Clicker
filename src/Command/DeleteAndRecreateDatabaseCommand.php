<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-recreate-db',
    description: 'Deletes and recreate the database.',
    hidden: false,
    aliases: ['app:drop-create-db']
)]
final class DeleteAndRecreateDatabaseCommand extends Command
{// DeleteAndRecreateDatabaseWithStructureAndDataCommand
    /** @var string|null */
    protected static $defaultName = 'app:clean-db';

    /** @var string|null */
    protected static $defaultDescription = 'Supprime et recrée la base de données avec sa structure et ses jeux de fausses données';

    /** execute. */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section(
            "Suppression de la base de donnée puis création d'une nouvelle avec structure et données pré-remplies"
        );

        $this->runSymfonyCommand($input, $output, 'doctrine:database:drop', true);
        $this->runSymfonyCommand($input, $output, 'doctrine:database:crate');
        $this->runSymfonyCommand($input, $output, 'doctrine:migrations:migrate');
        $this->runSymfonyCommand($input, $output, 'doctrine:fixtures:load');

        $io->success('RAS => Base de données toute propre avec ses data.');

        return Command::SUCCESS;
    }

    /** runSymfonyCommand. */
    private function runSymfonyCommand(
        InputInterface $input,
        OutputInterface $output,
        string $command,
        bool $forceOption = false
    ): void {
        $application = $this->getApplication();

        if (!$application instanceof Application) {
            throw new \LogicException('No application :(');
        }

        $command = $application->find($command);

        if ($forceOption) {
            $input = new ArrayInput(['--force' => true]);
        }

        $input->setInteractive(false);

        $command->run($input, $output);
    }
}
