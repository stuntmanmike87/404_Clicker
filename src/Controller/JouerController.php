<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe/contrôleur qui traite la fonctionnalité du jeu
 * exécuté par un utilisateur authentifié
 *
 * @method User|null getUser()
 */
final class JouerController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     *
     * @Route("/jouer", name="jouer")
     *
     * Fonction qui gère la page "jouer"
     *
     * jouer/index.html.twig : page du jeu
     */
    public function index(UserRepository $userRepository): Response
    {
        // dd($user);///** @var User $user *///if (! $user) {//dd('Not user connected');//$this->redirectToRoute('app_login');//}///** @var User $user */
        $user = $this->getUser();
        $changeLevelScore = $userRepository->changeLevel($user);

        return $this->render('jouer/index.html.twig', [
            'changeLevel' => $changeLevelScore,
        ]);
    }
}
