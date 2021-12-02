<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Services\MapManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class MapController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager, MapManager $mapManager)
    {
        $this->manager = $manager;
        $this->mapManager = $mapManager;
    }

    /**
     * @Route("/map", name="map")
     * @return Response
     */
    public function displayMap(): Response
    {
        $boat = $this->manager->getRepository(Boat::class)->findOneBy([]);
        $tiles = $this->manager->getRepository(Tile::class)->findAll();

        //Read Tiles
        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;

            //Give the type of tile where the boat is located
            if ($tile->getCoordX() === $boat->getCoordX() && $tile->getCoordY() === $boat->getCoordY()) {
                $type = $tile->getType();
            }
        }

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'type' => $type,
        ]);
    }

    /**
     * Restart the game (reset map)
     *
     * @Route("/restart", name="restart")
     * @return Response
     */
    public function restart()
    {
        
        $boat = $this->manager->getRepository(Boat::class)->findOneBy([]);
        $tiles = $this->manager->getRepository(Tile::class)->findBy(["hasTreasure" => true]);

        //Reset boat coords
        $boat->setCoordY(0);
        $boat->setCoordX(0);
        
        foreach ($tiles as $tile) {
            $tile->setHasTreasure(false);
        }
        //Set treasure
        $this->mapManager->getRandomIsland()->setHasTreasure(true);
        $this->manager->flush();

        return $this->redirectToRoute('map');
    }
}