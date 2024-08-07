<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur qui traite le profil de l'utilisateur.
 */
#[Route(path: '/user')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Fonction qui permet l'affichage d'un utilisateur (selon son identifiant)
     * user/show.html.twig : page d'affichage d'un joueur.
     */
    #[Route(path: '/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Fonction de modification d'un utilisateur
     * user/edit.html.twig : page de modification d'un joueur.
     */
    #[Route(path: '/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $plainPassword = $form->get('plainPassword');
        /** @var string $plainPassword */
        $plainPassword = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);

            $user->setPassword($encodedPassword);

            $this->entityManager->flush();

            // Destroy the currently active session.
            session_destroy();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Fonction de suppression d'un utilisateur
     * redirectToRoute('home') : redirection vers la page d'accueil.
     */
    #[Route(path: '/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        User $user,
        UserRepository $userRepository
    ): Response {
        if (
            $this->isCsrfTokenValid(
                'delete'.$user->getId(),
                (string) $request->request->get('_token')
            )
        ) {
            $userRepository->remove($user);
        }

        // Destroy the currently active session.
        session_destroy();

        return $this->redirectToRoute('home');
    }
}
