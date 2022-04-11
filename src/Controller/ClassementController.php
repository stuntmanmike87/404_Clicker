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
     */
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findByPointsDesc();

        return $this->render('classement/index.html.twig', [
            'users' => $users,
        ]);
    }
}
