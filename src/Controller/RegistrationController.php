<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\LevelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * Classe qui traite l'enregistrement d'un utilisateur.
 *
 * @method User|null getUser()
 */
final class RegistrationController extends AbstractController
{
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
        LevelRepository $levelRepository,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        JWTService $jwt,
        SendMailService $mail
    ): Response {
        if ($this->getUser() instanceof User) {// if ($this->getUser() !== null) {
            return $this->render('home/index.html.twig');
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // /** @var string $form_data */
        // $form_data = $form->get('plainPassword')->getData(); // $data = $form->get('email')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword');
            /** @var string $plainPassword */
            $plainPassword = $form->getData();
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, /* (string) */ $plainPassword)
            );//$user->setPassword($userPasswordHasher->hashPassword($user, $form_data));
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
            /* $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                $email
            ); */
            // do anything else you need here, like send an email
            $this->addFlash(
                'success',
                'Vous êtes bien inscrit, merci de bien regarder
                vos mails pour faire la vérification.'
            );

            $header = ['typ' => 'JWT', 'alg' => 'HS256',];

            /** @var array<string> $payload */ // @var (null|int)[] $payload
            $payload = ['user_id' => $user->getId(),];

            /** @var string $secret */
            $secret = $this->getParameter('app.jwtsecret');
            $token = $jwt->generate($header, $payload, $secret);

            /** @var array<string> $context() */
            $context = ['user' => $user, 'token' => $token];

            $mail->send(
                'no-reply@mysite.net',
                (string) $user->getEmail(),
                'Votre compte est activé sur ce site',
                'register',
                $context
            );

            /** @var Response $response */
            $response = $userAuthenticator->authenticateUser($user, $authenticator, $request);

            // return $this->redirectToRoute('home');
            return $response;
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
    #[Route('/verif/{token}', name: 'verify_user')]
    public function veriyfUser(
        string $token, // TokenInterface $token,
        JWTService $jwt,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response
    {
        /** @var string $param */
        $param = $this->getParameter('app.jwtsecret');

        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $param)) {
            $payload = $jwt->getPayload($token);

            $user = $userRepository->find($payload['user_id']);

            /** @var User $user */
            /** @var bool $verifiedUser */
            $verifiedUser = $user->getIsVerified();
            if (null !== $user && !$verifiedUser) {
                $user->setIsVerified(true);
                $em->flush();

                $this->addFlash('success', 'Cet utilisateur est activé');

                return $this->redirectToRoute('app_main');
            }
        }

        $this->addFlash('danger', 'Le token est invalide ou a expiré');

        return $this->redirectToRoute('app_login');
    }
}
