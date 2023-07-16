<?php

namespace App\EventListener;

use App\Entity\Dice;
use App\Service\DiceGenerator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Dice::class)]
class DiceListener
{

    private DiceGenerator $diceGenerator;

    public function __construct(DiceGenerator $diceGenerator)
    {
        $this->diceGenerator = $diceGenerator;
    }

    /**
     * Undocumented function
     *
     * @param Dice $dice
     * @param PrePersistEventArgs $event
     * @return void
     */
    public function prePersist(Dice $dice, PrePersistEventArgs $event): void
    {
        $this->diceGenerator->roll($dice);
    }

}