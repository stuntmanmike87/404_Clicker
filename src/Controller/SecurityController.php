<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Classe qui traite la connexion sécurisée d'un utilisateur
 */
final class SecurityController extends AbstractController
{
    /**
     * Fonction qui traite la connexion d'un utilisateur
     * security/login.html.twig : page de connexion
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //if ($this->getUser()) {
        //return $this->redirectToRoute('target_path');
        //}
        //get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        //last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]
        );
    }

    /**
     * Fonction qui déconnecte l'utilisateur
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('This method can be blank - ');
        //it will be intercepted by the logout key on your firewall.
    }
}
