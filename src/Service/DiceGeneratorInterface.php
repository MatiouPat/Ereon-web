<?php

namespace App\Service;

use App\Entity\Dice;

interface DiceGeneratorInterface
{

     /**
     * Roll a dice of a given value
     *
     * @param Dice $dice
     * @return Dice
     */
    public function roll(Dice $dice): Dice;

}