<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Level;

class LevelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i<4; $i++){
            $level = new Level();
            switch($i){
                case 0:
                    $level->setMaxPoints(0);
                    $level->setPathImg('/assets/images/level1_concept.png'); 
                    $level->setNomLevel('conception');
                    break;
                case 1:
                    $level->setMaxPoints(20);
                    $level->setPathImg('/assets/images/level2_dev.png'); 
                    $level->setNomLevel('developpement');
                    break;
                case 2:
                    $level->setMaxPoints(50);
                    $level->setPathImg('/assets/images/level3_prod.png'); 
                    $level->setNomLevel('production');
                    break;
                case 3:
                    $level->setMaxPoints(100);
                    $level->setPathImg('/assets/images/404.png'); 
                    $level->setNomLevel('404'); 
                    break;
            }
            $manager->persist($level);
        }

        $manager->flush();
    }
}
