<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AxiosController extends AbstractController
{
    /**
     * @Route("/axios", name="app_axios")
     */
    public function index(Request $request): Response
    {
        // Récupère la donnée envoyé par le front
        $data = json_decode($request->getContent(), true);
        //var_dump($data["points"]);exit;
        
        // Vérifie si la clé "points" existe
        if (isset($data["points"])) {
            $points = $data["points"];
            dump($points);
            // $userRepository->insertPoints($points);
        }
        return new JsonResponse(["message" => "ok"], 200);
    }
}
