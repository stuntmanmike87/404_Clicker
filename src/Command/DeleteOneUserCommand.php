<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\CustomValidatorForCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

final class DeleteOneUserCommand extends Command
{
    /**
     * @var string|null
     */
    protected static $defaultName = 'app:delete-one-user';

    /**
     * @var string|null
     */
    protected static $defaultDescription = 'Supprime un utilisateur en base de données';

    private SymfonyStyle $io;

    public function __construct(
        private readonly CustomValidatorForCommand $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, "L'e-mail de l'utilisateur");
    }

    /**
     * Executed after configure() to initialize properties based on the input arguments and options.
     */
    #[\Override]
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * execute function.
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $email */
        $email = $input->getArgument('email');

        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (!$user instanceof User) {
            throw new RuntimeException('AUCUN UTILISATEUR N\'EST PRESENT
                EN BASE DE DONNEES AVEC L\'E-MAIL SUIVANT : '.$email);
        }

        $userID = $user->getId();

        $this->entityManager->remove($user);

        $this->entityManager->flush();

        // $this->io
        $io->success(
            "L'UTILISATEUR AYANT L'ID {$userID} ET L'E-MAIL {$email} N'EXISTE PLUS DANS LA BASE DE DONNEES."
        );

        return Command::SUCCESS;
    }

    /**
     * Executed after initialize() and before execute().
     * Checks if some of the options/arguments are missing and ask the user for those values.
     */
    #[\Override]
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->io->section("SUPPRESSION D'UN UTILISATEUR EN BASE DE DONNEES");

        $this->enterEmail($input, $output);
    }

    /**
     * Sets the user email.
     */
    private function enterEmail(InputInterface $input, OutputInterface $output): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $emailQuestion = new Question("EMAIL DE L'UTILISATEUR :");

        /** @var callable|null $valid */
        $valid = $this->validator;

        $emailQuestion->setValidator($valid);

        /** @var string $email */
        $email = $helper->ask($input, $output, $emailQuestion);

        $input->setArgument('email', $email);
    }
}
