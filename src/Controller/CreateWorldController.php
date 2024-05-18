<?php

namespace App\Controller;

use App\Entity\Connection;
use App\Entity\Map;
use App\Entity\World;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

use function Symfony\Component\Clock\now;

#[AsController]
class CreateWorldController extends AbstractController
{

    public function __construct()
    {

    }

    public function __invoke(World $world)
    {
        $map = new Map();
        $map
            ->setName('Homepage')
            ->setWidth(1200)
            ->setHeight(1200)
            ->setHasDynamicLight(false)
            ->setWorld($world);
        $connection = new Connection();
        $connection
            ->setIsGameMaster(true)
            ->setLastConnectionAt(now())
            ->setUser($this->getUser())
            ->setCurrentMap($map);
        $world->addConnection($connection);
        return $world;
    }

}