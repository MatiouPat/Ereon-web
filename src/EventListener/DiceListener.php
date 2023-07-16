<?php

namespace App\EventListener;

use App\Entity\Dice;
use App\Service\DiceGenerator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Dice::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Dice::class)]
class DiceListener
{

    private DiceGenerator $diceGenerator;

    private HubInterface $hub;

    private SerializerInterface $serializer;

    public function __construct(DiceGenerator $diceGenerator, HubInterface $hub, SerializerInterface $serializer)
    {
        $this->diceGenerator = $diceGenerator;
        $this->hub = $hub;
        $this->serializer = $serializer;
    }

    /**
     * Undocumented function
     *
     * @param Dice $dice
     * @return void
     */
    public function prePersist(Dice $dice): void
    {
        $this->diceGenerator->roll($dice);
    }

    /**
     * Undocumented function
     *
     * @param Dice $dice
     * @return void
     */
    public function postPersist(Dice $dice):void
    {
        $update = new Update('http://example.com/dice', $this->serializer->serialize($dice, 'json', ['groups' => 'dice:read']));
        $this->hub->publish($update);
    }

}