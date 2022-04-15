<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsController extends AbstractController
{
    /**
     * @Route("/mentions", name="mentions_legales", methods={"GET"})
     * 
     * Fonction qui permet l'affichage de la page des mentions légales du site
     * 
     * @return mentions/index.html.twig page des mentions légales du site
     * 
     */
    public function index(): Response
    {
        return $this->render('mentions/index.html.twig', [
            'controller_name' => 'MentionsController',
        ]);
    }

}
