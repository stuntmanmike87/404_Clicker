<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AxiosController extends AbstractController
{
    /**
     * @Route("/save_axios", name="app_save_axios")
     * 
     * Fonction qui permet la sauvegarde des points du joueur
     * 
     * @param Request $request
     * @param $UserRepository $userRepository
     * @return JsonResponse
     */
    public function save(Request $request, UserRepository $userRepository): Response
    {
        // Récupère la donnée envoyé par le front
        $data = json_decode($request->getContent(), true);
        //var_dump($data["points"]);exit;
        
        // Vérifie si la clé "points" existe
        if (isset($data["points"])) {
            $user = $this->getUser();
            $points = $data["points"];
            $userRepository->insertPoints($points , $user);
            $userRepository->setLevelByNumberOfPoints($points, $user);
        }
        return new JsonResponse(["message" => "ajout des points dans la BDD"], 200);
    }

}
