<?php

namespace App\Service;

use App\Entity\Dice;

class DiceGenerator 
{

    /**
     * Roll a dice of a given value
     *
     * @param Dice $dice
     * @return Dice
     */
    public function roll(Dice $dice): Dice
    {
        $diceAttributes = [];
        $computation = $dice->getComputation();

        $computation = explode('d', $computation)[1];

        foreach(explode('-', $computation) as $key => $sub){
            if ($key != 0 ) {
                $sub = '-' . $sub;
            }
            foreach(explode('+', $sub) as $add) {
                array_push($diceAttributes, $add);
            }
        }

        $dice->setDiceNumber(array_shift($diceAttributes));
        $dice->setBrutValue($this->rollDice($dice->getDiceNumber()));
        $finalValue = $dice->getBrutValue();

        foreach($diceAttributes as $attribute) {
            $finalValue += intval($attribute);
        }
        
        $dice->setFinalValue($finalValue);
        
        return $dice;
    }

    /**
     * Undocumented function
     *
     * @param integer $diceFace
     * @return int
     */
    private function rollDice(int $diceFace): int
    {
        return mt_rand(1, $diceFace);
    }

}