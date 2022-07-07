<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Classe qui gère les fixtures d'un objet Level
 * qui représente le niveau d'un joueur
 */
final class LevelFixtures extends Fixture
{
    /**
     * Fonction qui permet de charger les fixtures:
     * enregistrement des niveaux en base de données
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadLevels($manager);

        $manager->flush();
    }

    /**
     * loadLevels function
     */
    private function loadLevels(ObjectManager $manager): void
    {
        foreach (
            $this->getLevelData() as [
                $maxPoints,
                $pathImg,
                $nomLevel,
                //$ref,
            ]
        ) {
            $level = new Level();

            $level->setMaxPoints(floatval($maxPoints));
            $level->setPathImg($pathImg);
            $level->setNomLevel(strval($nomLevel));
            //$ref = ...
            $this->addReference($nomLevel, $level);

            $manager->persist($level);
        }

        //$manager->flush();
    }

    /**
     * getLevelData function
     *
     * @return array<array<string>>
     */
    private function getLevelData(): array
    {
        return [//$levelData = [];
            [
                '0',
                '/assets/images/level1_concept.png',
                'conception',
            ],
            [
                '20',
                '/assets/images/level2_dev.png',
                'developpement',
            ],
            [
                '50',
                '/assets/images/level3_prod.png',
                'production',
            ],
            [
                '100',
                '/assets/images/404.png',
                '404',
            ],
            [
                '200',
                '/assets/images/500.webp',
                '500',
            ],
            [
                '500',
                '/assets/images/403.png',
                '403',
            ],
        ];
    }
}
