<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Services\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boat")
 */
class BoatController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager, MapManager $mapManager)
    {
        $this->manager = $manager;
        $this->mapManager = $mapManager;
    }

    /**
     * Move the boat to coord x,y
     * 
     * @Route("/move/{x}/{y}", name="moveBoat", requirements={"x"="\d+", "y"="\d+"}))
     * @return Response
     */
    public function moveBoat(int $x, int $y): Response
    {
        $boat = $this->manager->getRepository(Boat::class)->findOneBy([]);
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $this->manager->flush();

        return $this->redirectToRoute('map');
    }

    /**
     * Move the boat with the direction North, South, West or Est
     * 
     * @Route("/direction/{direction}", name="moveDirection", methods={"GET", "POST"}, requirements={"direction"="[NSWE]"}))
     * @param [string] $direction
     * @return Response
     */
    public function moveDirection(string $direction): Response
    {
        $boat = $this->manager->getRepository(Boat::class)->findOneBy([]);

        //Moove the boat N, S, W, E when clicked
        if($direction === 'N') {
            if($this->mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() - 1)) {
                $boat->setCoordY($boat->getCoordY() - 1);
            } else {
                $this->addFlash('danger', 'Heeehoo! Ou vas tu moussaillon? Le bateau doit rester sur la carte.');
            }
        }
        if($direction === 'S') {
            if($this->mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() + 1)) {
                $boat->setCoordY($boat->getCoordY() + 1);
            } else {
                $this->addFlash('danger', 'Attention, votre bateau ne peut pas sortir de la carte!');
            }
        }
        if($direction === 'E') {
            if($this->mapManager->tileExists($boat->getCoordX() + 1, $boat->getCoordY())) {
                $boat->setCoordX($boat->getCoordX() + 1);
            } else {
                $this->addFlash('danger', 'Attention, votre bateau ne peut pas sortir de la carte!');
            }
        }
        if($direction === 'W') {
            if($this->mapManager->tileExists($boat->getCoordX() - 1, $boat->getCoordY())) {
                $boat->setCoordX($boat->getCoordX() - 1);
            } else {
                $this->addFlash('danger', 'Attention, votre bateau ne peut pas sortir de la carte!');
            }
        }

        $this->manager->flush();

        //Restart the game if treasure is founded
        if($this->mapManager->checkTreasure($boat) === true) {
            $this->addFlash('success', 'Zehahahahahaha! Bravo matelot tu as trouvé le trésor de Rackham le Rouge!');
            return $this->redirectToRoute('restart');
        }

        return $this->redirectToRoute('map');
    }
}
