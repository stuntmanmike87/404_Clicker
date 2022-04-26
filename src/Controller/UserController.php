<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user")
 * 
 * Contrôleur qui traite le profil de l'utilisateur
 * 
 */
class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     * 
     * Fonction qui permet l'affichage d'un utilisateur (selon son identifiant)
     * 
     * @param User $user
     * 
     * @return user/show.html.twig page d'affichage d'un joeur
     * 
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     * 
     * Fonction de modification d'un utilisateur
     * 
     * @param Request $request
     * 
     * @param User $user
     * 
     * @param UserPasswordHasherInterface $userPasswordHasher
     * 
     * @return user/edit.html.twig page de modification d'un joueur
     * 
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // Destroy the currently active session.
            session_destroy();

            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     * 
     * Fonction de suppression d'un utilisateur
     * 
     * @param Request $request
     * 
     * @param User $user
     * 
     * @param UserRepository $userRepository
     * 
     * @return home : redirection vers la page d'accueil
     * 
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        // Destroy the currently active session.
        session_destroy();

        return $this->redirectToRoute('home');
    }
}
