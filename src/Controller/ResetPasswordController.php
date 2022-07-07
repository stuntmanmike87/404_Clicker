<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/reset-password")
 *
 * Contrôleur qui gère la réinitialisation du mot de passe de l'utilisateur
 */
final class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    /**
     * Reset password helper
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var ResetPasswordHelperInterface $resetPasswordHelper
     */
    private $resetPasswordHelper;

    /**
     * Entity manager
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        EntityManagerInterface $entityManager
    )
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * Display & process form to request a password reset.
     *
     * @Route("", name="app_forgot_password_request")
     *
     * Fonction qui traite la demande de réinitialisation du mot de passe
     *
     * reset_password/request.html.twig :
     * page de demande de réinitialisation du mot de passe
     */
    public function request(
        Request $request,
        MailerInterface $mailer,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                strval($form->get('email')->getData()),
                $mailer,
                $translator
            );
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/check-email", name="app_check_email")
     *
     * Fonction qui traite la récupération
     * de l'adresse e-mail de l'utilisateur,
     * afin de traiter sa demande de réinitialisation du mot de passe
     *
     * reset_password/check_email.html.twig :
     * page de récupération de l'adresse e-mail de l'utilisateur
     */
    public function checkEmail(): Response
    {
        //Generate a fake token if the user does not exist
        //or someone hit this page directly.
        //This prevents exposing whether or not a user
        //was found with the given email address or not
        $resetToken = $this->getTokenObjectFromSession();
        if ($resetToken === null) {
            $resetToken = $this
                ->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * Validates and process the reset URL
     * that the user clicked in their email.
     *
     * @Route("/reset/{token}", name="app_reset_password")
     *
     * Fonction qui traite la réinitialisation du mot de passe
     *
     * reset_password/reset.html.twig :
     * page de réinitialisation du mot de passe
     */
    public function reset(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        TranslatorInterface $translator,
        ?string $token = null
    ): Response
    {//Your function is too long. Currently using 49 lines.
        if ($token) {//Can be up to 20 lines.
            //We store the token in session and remove it
            //from the URL, to avoid the URL being
            //loaded in a browser and potentially
            //leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if ($token === null) {
            throw $this->createNotFoundException(
                'No reset password token found in the URL or in the session.'
            );
        }

        try {
            $user = $this
                ->resetPasswordHelper
                ->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash(
                'reset_password_error',
                sprintf(
                    '%s - %s',
                    $translator->trans(
                        ResetPasswordExceptionInterface::
                        MESSAGE_PROBLEM_VALIDATE,
                        [],
                        'ResetPasswordBundle'
                    ),
                    $translator->trans(
                        $e->getReason(),
                        [],
                        'ResetPasswordBundle'
                    )
                )
            );

            return $this->redirectToRoute('app_forgot_password_request');
        }

        //The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            //Encode(hash) the plain password, and set it.
            /** @var User $user */
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                strval($form->get('plainPassword')->getData())
            );

            /** @var User $user */
            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            //The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    /**
     * Fonction privée qui traite l'envoi d'une adresse e-mail
     * de réinitialisation de mot de passe
     */
    private function processSendingPasswordResetEmail(
        string $emailFormData,
        MailerInterface $mailer,
        TranslatorInterface $translator
    ): RedirectResponse
    {
        //$user = $this->entityManager->getRepository(User::class)
        //->findOneBy(['email' => $emailFormData,]);
        //Call to method findOneBy()
        //on an unknown class app\Entity\UserRepository.

        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $repository->findOneBy(['email' => $emailFormData,]);

        //Do not reveal whether a user account was found or not.
        //if (!$user) {
        //return $this->redirectToRoute('app_check_email');
        //}

        try {
            $resetToken = $this
                ->resetPasswordHelper
                ->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            //If you want to tell the user why a reset email was not sent,
            //uncomment the lines below and change the redirect to
            //'app_forgot_password_request'.
            //Caution: This may reveal if a user is registered or not.
            //
            //$this->addFlash('reset_password_error', sprintf(
            //'%s - %s',
            //$translator->trans(ResetPasswordExceptionInterface::
            //MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //$translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            //));

            return $this->redirectToRoute('app_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('mail@your-mail.com', 'Email Bot'))
            ->to(strval($user->getEmail()))
            ->subject('Votre demande de réinitialisation de mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context(['resetToken' => $resetToken,])
        ;

        $mailer->send($email);

        //Store the token object in session
        //for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}
