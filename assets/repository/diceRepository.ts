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
    
    public async createDice(userId: number, worldId: number, computation: string, personageId?: number): Promise<void>
    {
        if(personageId === undefined) {
            return axios({
                method: 'POST',
                url: '/api/dices',
                data: {
                    computation: computation,
                    launcher: '/api/users/' + userId,
                    world: '/api/worlds/' + worldId
                }
            })
        }else {
            return axios({
                method: 'POST',
                url: '/api/dices',
                data: {
                    computation: computation,
                    launcher: '/api/users/' + userId,
                    world: '/api/worlds/' + worldId,
                    personage: '/api/personages/' + personageId
                }
            })
        }
    }

}