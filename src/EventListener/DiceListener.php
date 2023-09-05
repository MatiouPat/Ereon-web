<?php

namespace App\EventListener;

use App\Entity\Dice;
use App\Service\DiceGeneratorInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Dice::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Dice::class)]
class DiceListener
{

    private DiceGeneratorInterface $diceGenerator;

    private MessageBusInterface $bus;

    private SerializerInterface $serializer;

    public function __construct(DiceGeneratorInterface $diceGenerator, MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->diceGenerator = $diceGenerator;
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    /**
     * Roll the dice to complete this information
     *
     * @param Dice $dice
     * @return void
     */
    public function prePersist(Dice $dice): void
    {
        $this->diceGenerator->roll($dice);
    }

    /**
     * Send dice to client-side after modification
     *
     * @param Dice $dice
     * @return void
     */
    public function postPersist(Dice $dice):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/dice', $this->serializer->serialize($dice, 'json', ['groups' => 'dice:read']));
        $this->bus->dispatch($update);
    }

}