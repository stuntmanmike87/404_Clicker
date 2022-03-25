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
     * Fonction qui permet l'affichage de la page est la page mentions légales du site
     * 
     * 
     * @return mentions.twig 
     */
    public function mentions(): Response
    {
        return $this->render('mentions.twig', [
            'controller_name' => 'MentionsController',
        ]);
    }

}
