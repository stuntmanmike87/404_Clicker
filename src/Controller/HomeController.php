<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * 
     * Fonction qui dirige l'affichage de la page d'accueil
     * 
     * @return home/index.html.twig affiche la page d'accueil
     * 
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/mentions", name="mentions_legales", methods={"GET"})
     * 
     * Fonction qui permet l'affichage de la page des mentions légales du site
     * 
     * @return home/mentions.html.twig page des mentions légales du site
     * 
     */
    public function legalNotices(): Response
    {
        return $this->render('home/mentions.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/plan", name="plan")
     * 
     * Fonction qui dirige vers la page de plan du site
     * 
     * @return home/plan.html.twig page "plan du site"
     * 
     */
    public function sitemap(): Response
    {
        return $this->render('home/plan.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/presentation", name="presentation")
     * 
     * Fonction qui pointe vers la page de présentation du jeu et de l'équipe
     * 
     * @return home/presentation.html.twig page de présentation
     * 
     */
    public function presentation(): Response
    {
        return $this->render('home/presentation.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/privacy", name="privacyPolicy", methods={"GET"})
     * 
     * Fonction qui permet l'affichage de la page de la politique de confidentialité
     * 
     * 
     * @return home/privacyPolicy.html.twig 
     */
    public function privacyPolicy(): Response
    {
        return $this->render('home/privacyPolicy.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/error403", name="error403", methods={"GET"})
     * 
     * Fonction qui permet l'affichage de la page erreur 403
     * 
     * @return home/error403.html.twig 
     */
    public function error403(): Response
    {
        return $this->render('home/error403.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }

    /**
     * @Route("/error404", name="error404", methods={"GET"})
     * 
     * Fonction qui permet l'affichage de la page erreur 404
     * 
     * @return home/error404.html.twig 
     */
    public function error404(): Response
    {
        return $this->render('home/error404.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }
}
