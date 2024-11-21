<?php

namespace App\Controller;

use App\Entity\Connection;
use App\Entity\Map;
use App\Entity\Skill;
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
        //Default map creation
        $map = new Map();
        $map
            ->setName('Homepage')
            ->setWidth(1200)
            ->setHeight(1200)
            ->setHasDynamicLight(false)
            ->setWorld($world);

        //Creating the connection between the creator of the world and the created world
        $connection = new Connection();
        $connection
            ->setIsGameMaster(true)
            ->setLastConnectionAt(now())
            ->setUser($this->getUser())
            ->setCurrentMap($map);
        $world->addConnection($connection);

        $attributes = $world->getAttributes();
        /**
         * @var Skill $skill
         */
        foreach ($world->getSkills() as $skill)
        {
            $acronymToFind = $skill->getAttribute()->getAcronym();
            $attributeToSearch = $attributes->filter(function ($item) use ($acronymToFind) {
                return $item->getAcronym() === $acronymToFind;
            })->first();
            $skill->setAttribute($attributeToSearch);
        }

        return $world;
    }

}