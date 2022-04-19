<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassementController extends AbstractController
{
    /**
     * @Route("/classement", name="classement")
     * 
     * Fonction qui permet l'affichage du classement des joueurs
     * 
     * @param $UserRepository $repository
     * 
     * @return classement/index.html.twig page du classement des joueurs
     * 
     */
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findByPointsDesc();

        return $this->render('classement/index.html.twig', [
            'users' => $users,
        ]);
    }
}