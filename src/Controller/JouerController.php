<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class JouerController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/jouer", name="jouer")
     * 
     * Fonction qui gère la page "jouer"
     * 
     * @param UserRepository $userRepository
     * 
     * @return jouer/index.html.twig page du jeu
     * 
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        // dd($user);

        if (!$user) {
            // dd('Not user connected');
            $this->redirectToRoute('app_login');
        }

        $changeLevelScore = $userRepository->changeLevel($user);

        return $this->render('jouer/index.html.twig', [
            'changeLevel' => $changeLevelScore,
        ]);
    }
}
