<?php

namespace App\DataFixtures;

use App\Entity\Boat;
use App\Entity\Tile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tiles = [
            ['mer', 'mer', 'mer', 'mer', 'mer', 'ile', 'mer', 'mer', 'mer', 'port', 'mer', 'mer'],
            ['mer', 'port', 'mer', 'ile', 'mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'ile', 'mer'],
            ['mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'ile', 'mer', 'mer', 'mer'],
            ['mer', 'ile', 'mer', 'mer', 'ile', 'mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'mer'],
            ['mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'ile', 'mer', 'mer', 'port', 'mer'],
            ['ile', 'mer', 'mer', 'mer', 'port', 'mer', 'mer', 'mer', 'mer', 'mer', 'mer', 'ile'],
        ];

        foreach ($tiles as $y => $line) {
            foreach ($line as $x => $type) {
                $tile = new Tile();
                $tile->setType($type);
                $tile->setCoordX($x);
                $tile->setCoordY($y);
                $manager->persist($tile);
            }
        }

        $boat = new Boat();
        $boat->setCoordX(0);
        $boat->setCoordY(0);
        $boat->setName('Black Pearl');
        $manager->persist($boat);

        $manager->flush();
    }
}