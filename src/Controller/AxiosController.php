<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe/contrôleur qui traite l'enregistrement des points
 * d'un utilisateur dans la base de données
 *
 * @method User|null getUser()
 */
final class AxiosController extends AbstractController
{
    /**
     * @Route("/save_axios", name="app_save_axios")
     *
     * Fonction qui permet la sauvegarde des points du joueur
     */
    public function save(
        Request $request,
        UserRepository $userRepository
    ): Response
    {
        //Récupère la donnée envoyée par le front-end
        /** @var array<string> $data */
        $data = json_decode(
            (string) $request->getContent(),
            true,
            512, JSON_THROW_ON_ERROR
        );
        //var_dump($data["points"]);exit;

        //Vérifie si la clé "points" existe
        if (isset($data["points"])) {
            /** @var User $user */
            $user = $this->getUser();
            /** @var float $points */
            $points = $data["points"];
            $userRepository->insertPoints($points, $user);
            $userRepository->checkLevelByMaxPoints($points, $user);
        }

        return new JsonResponse(
            ["message" => "ajout des points dans la BDD"],
            200
        );
    }
}
