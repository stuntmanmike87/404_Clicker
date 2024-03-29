<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Classe/contrôleur qui traite la fonctionnalité du jeu
 * exécuté par un utilisateur authentifié.
 *
 * @method User|null getUser()
 */
final class GameController extends AbstractController
{
    /**
     * Fonction qui gère la page "jouer"
     * jouer/index.html.twig : page du jeu.
     */
    #[Route(path: '/jouer', name: 'jouer')]
    public function index(UserRepository $userRepository): Response
    {
        // dd($user);/
        // ** @var User $user */
        // if (! $user) {
        // dd('Not user connected');
        // $this->redirectToRoute('app_login');
        // }
        /** @var User $user */
        $user = $this->getUser();
        $changeLevelScore = $userRepository->changeLevel($user);

        return $this->render('jouer/index.html.twig', [
            'changeLevel' => $changeLevelScore,
        ]);
    }
}
