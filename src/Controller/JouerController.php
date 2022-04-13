<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JouerController extends AbstractController
{
    /**
     * @Route("/jouer", name="jouer")
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
