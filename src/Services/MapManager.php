<?php

namespace App\Services;

use App\Entity\Boat;
use App\Entity\Tile;
use Doctrine\ORM\EntityManagerInterface;


class MapManager
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Check if the tile with these coordinates exists or not.
     * 
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function tileExists(int $x, int $y): bool
    {
        $tiles = $this->manager->getRepository(Tile::class)->findAll();

        foreach($tiles as $tile) {
            if ($tile->getCoordX() === $x &&
                $tile->getCoordY() === $y) {
                return true;
            }
        }
        return false;
    }

    /**
     * Randomly give an island
     *
     * @return Tile
     */
    public function getRandomIsland(): Tile
    {
        $islands = $this->manager->getRepository(Tile::class)->findBy(["type" => "ile"]);
        return $islands[array_rand($islands, 1)];
    }

    /**
     * Check if the boat is on the spot where the treasure is
     *
     * @param Boat $boat
     * @return boolean
     */
    public function checkTreasure(Boat $boat): bool
    {
        $boatX = $boat->getCoordX();
        $boatY = $boat->getCoordY();

        $tile = $this->manager->getRepository(Tile::class)->findOneBy([
            "coordX" => $boatX,
            "coordY" => $boatY
        ]);

        if($tile->getHasTreasure()) {
            return true;
        } else {
            return false;
        }
    }
}