<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    /**
     * Fonction qui dirige l'affichage de la page d'accueil
     * home/index.html.twig : affiche la page d'accueil
     */
    #[Route(path: '/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Fonction qui permet l'affichage de la page des mentions légales du site
     * home/mentions.html.twig : page des mentions légales du site
     */
    #[Route(path: '/mentions', name: 'mentions_legales', methods: ['GET'])]
    public function legalNotices(): Response
    {
        return $this->render('home/mentions.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Fonction qui dirige vers la page de plan du site
     * home/plan.html.twig : page "plan du site"
     */
    #[Route(path: '/plan', name: 'plan')]
    public function sitemap(): Response
    {
        return $this->render('home/plan.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Fonction qui pointe vers la page de présentation du jeu et de l'équipe
     * home/presentation.html.twig : page de présentation
     */
    #[Route(path: '/presentation', name: 'presentation')]
    public function presentation(): Response
    {
        return $this->render('home/presentation.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Fonction qui permet l'affichage de la page
     * de la politique de confidentialité
     * home/privacyPolicy.html.twig :
     * page de la police de confidentialité du site
     */
    #[Route(path: '/privacy', name: 'privacyPolicy', methods: ['GET'])]
    public function privacyPolicy(): Response
    {
        return $this->render('home/privacyPolicy.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Fonction qui permet l'affichage de la page erreur 403
     * home/error403.html.twig : page d'erreur 403
     */
    #[Route(path: '/error403', name: 'error403', methods: ['GET'])]
    public function error403(): Response
    {
        return $this->render('home/error403.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Fonction qui permet l'affichage de la page erreur 404
     * home/error404.html.twig : page d'erreur 404
     */
    #[Route(path: '/error404', name: 'error404', methods: ['GET'])]
    public function error404(): Response
    {
        return $this->render('home/error404.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
