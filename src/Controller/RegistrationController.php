<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\LevelRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * Classe qui traite l'enregistrement d'un utilisateur.
 *
 * @method User|null getUser()
 */
final class RegistrationController extends AbstractController
{
    public function __construct(private readonly EmailVerifier $emailVerifier)
    {
    }

    /**
     * Fonction qui traite l'inscription d'un utilisateur (joueur)
     * return $this->redirectToRoute('home') : redirection
     * vers la page d'accueil, si l'enregistrement est réussi.
     */
    #[Route(path: '/inscription', name: 'inscription')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        LevelRepository $levelRepository
    ): Response {
        if ($this->getUser() instanceof User) {// if ($this->getUser() !== null) {
            return $this->render('home/index.html.twig');
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // vérifier si les champs saisis pour cet utilisateur
            // existent déjà dans la base de données
            // Don't use the following if statement [always false condition]
            // but make a unique entity [constraint(s)]
            /* if ($this->getUser()) {
                $this->addFlash('error',
                    'Utilisateur et/ou email déjà enregistré');
                return $this->redirectToRoute('inscription');
            } */

            $plainPassword = $form->get('plainPassword');
            /** @var string $plainPassword */
            $plainPassword = $form->getData();
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, /* (string) */ $plainPassword)
            );
            // initialisation des propriétés (champs) d'un user à l'enregistrement
            $user->setRoles(['ROLE_USER']);
            $user->setPoints(0);
            $user->setIsVerified(false);
            $user->setIsDeleted(false);
            $user->setIsExpired(false);
            $user->setFullName('');
            $user->setLevel($levelRepository->find(1));
            // persistance d'un user en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())->from(
                new Address(
                    'mail@your-mail.com',
                    'Email Bot'
                )
            );
            $email = (new TemplatedEmail())->to((string) $user->getEmail());
            $email = (new TemplatedEmail())->subject(
                'Veuillez confirmer votre adresse email'
            );
            $email = (new TemplatedEmail())->htmlTemplate(
                'registration/confirmation_email.html.twig'
            );
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                $email
            );
            // do anything else you need here, like send an email
            $this->addFlash(
                'success',
                'Vous êtes bien inscrit, merci de bien regarder
                vos mails pour faire la vérification.'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form, // ->createView(),
        ]);
    }

    /**
     * Fonction de vérification de l'adresse e-mail de l'utilisateur
     * return $this->redirectToRoute('inscription') :
     * redirection vers la page d'inscription.
     */
    #[Route(path: '/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $verifyEmailException) {
            $this->addFlash(
                'verify_email_error',
                $translator->trans(
                    $verifyEmailException->getReason(),
                    [],
                    'VerifyEmailBundle'
                )
            );

            return $this->redirectToRoute('home');
        }

        // to_do_task: Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash(
            'success',
            "Votre adresse email vient d'être vérifiée avec succès."
        );

        return $this->redirectToRoute('inscription');
    }
}
