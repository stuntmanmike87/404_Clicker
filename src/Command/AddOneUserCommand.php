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
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AddOneUserCommand extends Command
{
    //EMAIL_ARGUMENT_DESCRIPTION
    ///** @var string */
    private const EMAIL_ARG = "L'e-mail de l'utilisateur";

    //PASSWORD_ARGUMENT_DESCRIPTION
    ///** @var string */
    private const PW_ARG = "Le mot de passe en clair de l'utilisateur";

    //ROLE_ARGUMENT_DESCRIPTION
    ///** @var string */
    private const ROLE_ARG = "Le rôle de l'utilisateur";

    //ISVERIFIED_ARGUMENT_DESCRIPTION
    ///** @var string */
    private const ISV_ARG = "Le statut du compte l'utilisateur (actif)";

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string|null $defaultName
     */
    protected static $defaultName = 'app:add-one-user';

    private SymfonyStyle $io;

    public function __construct(
        private readonly CustomValidatorForCommand $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $encoder,
        private readonly UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, self::EMAIL_ARG);
        $this->addArgument('plainPassword', InputArgument::REQUIRED, self::PW_ARG);
        $this->addArgument('role', InputArgument::REQUIRED, self::ROLE_ARG);
        $this->addArgument('isVerified', InputArgument::REQUIRED, self::ISV_ARG);
    }

    /**
     * Executed after configure() to initialize properties
     * based on the input arguments and options.
     */
    protected function initialize(
        InputInterface $input,
        OutputInterface $output
    ): void {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * Executed after initialize() and before execute().
     * Checks if some of the options/arguments
     * are missing and ask the user for those values.
     */
    protected function interact(
        InputInterface $input,
        OutputInterface $output
    ): void {
        $this->io->section("AJOUT D'UN UTILISATEUR EN BASE DE DONNEES");

        $this->enterEmail($input, $output);

        $this->enterPassword($input, $output);

        $this->enterRole($input, $output);

        $this->enterIsVerified($input, $output);
    }

    /**
     * execute function
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        /** @var string $email */
        $email = $input->getArgument('email');

        $io = new SymfonyStyle($input, $output);

        /** @var string $plainPassword */
        $plainPassword = $input->getArgument('plainPassword');

        /** @var array<string> $role */
        $role = [$input->getArgument('role')];

        /** @var bool $isVerified */
        $isVerified = $input
            ->getArgument('isVerified') !== 'INACTIF';

        $user = new User();

        $user->setEmail($email);
        $user->setPassword(
            $this->encoder->hashPassword($user, $plainPassword)
        );
        $user->setIsVerified($isVerified);
        $user->setRoles($role);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        /* $this->io */$io->success('UN NOUVEL UTILISATEUR 
            EST PRESENT EN BASE DE DONNEES !');

        return Command::SUCCESS;
    }

    /**
     * Sets the user email.
     */
    private function enterEmail(
        InputInterface $input,
        OutputInterface $output
    ): void {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $emailQuestion = new Question("E-MAIL DE L'UTILISATEUR :");

        /** @var callable|null $valid */
        $valid = $this->validator;

        $emailQuestion->setValidator($valid);//,'validateEmail');

        /** @var string $email */
        $email = $helper->ask($input, $output, $emailQuestion);

        if ($this->isUserAlreadyExists($email) instanceof \App\Entity\User) {//if ($this->isUserAlreadyExists($email) !== null) {
            throw new RuntimeException(
                sprintf(
                    "UTILISATEUR DEJA PRESENT EN BASE DE DONNEES 
                    AVEC L'E-MAIL SUIVANT : %s",
                    $email
                )
            );
        }

        $input->setArgument('email', $email);
    }

    /**
     * Checks if an user already exists in database
     * with the email entered by the user in the CLI.
     */
    private function isUserAlreadyExists(string $email): ?User
    {
        return $this->userRepository->findOneBy([
            'email' => $email,
        ]);
    }

    /**
     * Sets the password entered in $input variable if it's valid.
     */
    private function enterPassword(
        InputInterface $input,
        OutputInterface $output
    ): void {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $passwordQuestion = new Question(
            "MOT DE PASSE EN CLAIR DE L'UTILISATEUR 
            (ALGORITHME DE HASHAGE ARGON2ID) :"
        );

        /** @var callable|null $valid */
        $valid = $this->validator;

        $passwordQuestion->setValidator($valid);

        //Hide password when typing
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $passwordQuestion);

        $input->setArgument('plainPassword', $password);
    }

    /**
     * Sets the role choice in $input variable if it's valid.
     */
    private function enterRole(
        InputInterface $input,
        OutputInterface $output
    ): void {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $roleQuestion = new ChoiceQuestion(
            "SELECTION DU ROLE DE L'UTILISATEUR",
            ['ROLE_USER', 'ROLE_ADMIN'],
            'ROLE_USER'
        );

        $roleQuestion->setErrorMessage('ROLE UTILISATEUR INVALIDE');

        /** @var string $role */
        $role = $helper->ask($input, $output, $roleQuestion);

        $output->writeln(
            sprintf('<info>ROLE UTILISATEUR PRIS EN COMPTE : %s</info>', $role)
        );

        $input->setArgument('role', $role);
    }

    /**
     * Sets the isVerified choice in $input variable if it's valid.
     */
    private function enterIsVerified(
        InputInterface $input,
        OutputInterface $output
    ): void {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $isVerifiedQuestion = new ChoiceQuestion(
            "SELECTION DU STATUT D'ACTIVATION DE L'UTILISATEUR :",
            ['INACTIF', 'ACTIF'],
            'ACTIF'
        );

        $isVerifiedQuestion->setErrorMessage(
            "STATUT D'ACTIVATION DU COMPTE UTILISATEUR INVALIDE."
        );

        /** @var bool $isVerified */
        $isVerified = $helper->ask($input, $output, $isVerifiedQuestion);

        $output->writeln(
            "<info>STATUT D'ACTIVATION 
            DU COMPTE UTILISATEUR PRIS EN COMPTE : {$isVerified}</info>"
        );

        $input->setArgument('isVerified', $isVerified);
    }
}
