<?php

namespace App\Controller;

use App\Entity\Map;
use App\Repository\WorldRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Validator\Exception\ValidatorException;

#[AsController]
class DeleteMapController extends AbstractController
{

    private WorldRepository $worldRepository;

    public function __construct(WorldRepository $worldRepository)
    {
        $this->worldRepository = $worldRepository;
    }

    public function __invoke(Map $map): Map
    {
        $targetMap = null;
        if(count($map->getConnections()) > 0) {
            $world = $this->worldRepository->findById($map->getWorld()->getId());
            foreach($world->getMaps() as $mapWorld) {
                if($map->getId() != $mapWorld->getId()) {
                    $targetMap = $mapWorld;
                    break;
                }
            }
            if($targetMap != null) {
                foreach($map->getConnections() as $connection) {
                    $connection->setCurrentMap($targetMap);
                }
            }else {
                throw new ValidatorException("Vous ne pouvez pas supprimer la dernière carte d'un monde");
            }
        }
        return $map;
    }

}