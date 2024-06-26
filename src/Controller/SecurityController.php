<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Classe qui traite la connexion sécurisée d'un utilisateur.
 */
final class SecurityController extends AbstractController
{
    /**
     * Fonction qui traite la connexion d'un utilisateur
     * security/login.html.twig : page de connexion.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        // return $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]
        );
    }

    /**
     * Fonction qui déconnecte l'utilisateur.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('This method can be blank - ');
        // it will be intercepted by the logout key on your firewall.
    }

    #[Route('/forgotten-password', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        JWTService $jwt,
        SendMailService $mail
    ): Response {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        /** @var string $form_data */
        $form_data = $form->get('email')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $form_data */
            $form_data = $form->get('email')->getData();

            /** @var User $user */
            $user = $userRepository->findOneByEmail($form_data);

            if ($user instanceof User) { // if (null !== $user) {
                $header = ['typ' => 'JWT', 'alg' => 'HS256',];

                /** @var array<string> $payload */
                $payload = ['user_id' => $user->getId(),];

                /** @var string $param */
                $param = $this->getParameter('app.jwtsecret');
                $token = $jwt->generate($header, $payload, $param);

                $url = $this->generateUrl(
                    'reset_pass',
                    ['token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                /** @var array<string> $context */
                $context = ['url' => $url, 'user' => $user];

                $mail->send(
                    'no-reply@etrade.com',
                    (string) $user->getEmail(),
                    'Password reset',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Email sent successfully');

                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('danger', 'Some problem occured');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form,
        ]);
    }

    #[Route('/forgotten-password/{token}', name: 'reset_password')]
    public function resetPassword(
        string $token,
        JWTService $jwt,
        UserRepository $userRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        /** @var string $param */
        $param = $this->getParameter('app.jwtsecret');

        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $param)) {
            $payload = $jwt->getPayload($token);

            $user = $userRepository->find($payload['user_id']);

            if (null !== $user) {
                $form = $this->createForm(ResetPasswordFormType::class);

                $form->handleRequest($request);

                /** @var string $data */
                $data = $form->get('password')->getData();
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword($passwordHasher->hashPassword($user, $data));

                    $em->flush();

                    $this->addFlash('success', 'Password changed with success');

                    return $this->redirectToRoute('app_login');
                }

                return $this->render('security/reset_password.html.twig', [
                    'passForm' => $form->createView(),
                ]);
            }
        }

        $this->addFlash('danger', 'Invalid or expired token');

        return $this->redirectToRoute('app_login');
    }
}
