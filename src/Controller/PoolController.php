<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PoolController extends AbstractController
{
    /**
     * @Route("/classement", name="classement")
     *
     * Fonction qui permet l'affichage du classement des joueurs
     *
     * classement/index.html.twig : page du classement des joueurs
     */
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findByPointsDesc();

        return $this->render('classement/index.html.twig', [
            'users' => $users,
        ]);
    }
}
