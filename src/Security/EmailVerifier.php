<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * Classe qui traite la vérification des adresses e-mail
 */
final class EmailVerifier
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var VerifyEmailHelperInterface $verifyEmailHelper
     */
    private $verifyEmailHelper;
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var MailerInterface $mailer
     */
    private $mailer;
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * Constructeur de la classe EmailVerifier
     */
    public function __construct(
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer,
        EntityManagerInterface $manager
    )
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }
    /**
     * Fonction qui traite l'envoi d'une adresse e-mail de confirmation
     */
    public function sendEmailConfirmation(
        string $verifyEmailRouteName,
        User $user,
        TemplatedEmail $email
    ): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            strval($user->getId()),
            strval($user->getEmail()),
            //['id' => $user->getId()]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents
            ->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents
            ->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents
            ->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * Fonction qui traite la requête de l'envoi
     * de l'adresse e-mail de confirmation
     *
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(
        Request $request,
        User $user
    ): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation(
            $request->getUri(),
            strval($user->getId()),
            strval($user->getEmail())
        );

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
