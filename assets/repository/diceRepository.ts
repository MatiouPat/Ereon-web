import axios from "axios";
import { Dice } from "../entity/dice";

export class DiceRepository
{

    public async findAllDices(): Promise<Dice[]>
    {
        return axios({
            method: 'GET',
            url: '/api/dices'
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }
    
    public async createDice(personageUri: string, computation: string): Promise<void>
    {
        return axios({
            method: 'POST',
            url: '/api/dices',
            data: {
                computation: computation,
                personage: personageUri
            }
        })
    }

}