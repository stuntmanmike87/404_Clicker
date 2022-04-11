<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JouerController extends AbstractController
{
    /**
     * @Route("/jouer", name="jouer")
     */
    public function index(): Response
    {
        return $this->render('jouer/index.html.twig', [
            'controller_name' => 'JouerController',
        ]);
    }
}
