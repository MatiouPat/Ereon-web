import { Dice } from "../entity/dice";
import { AbstractRepository } from "../utils/abstractRepository";

export class DiceRepository extends AbstractRepository
{
    public async findAllDices(): Promise<Dice[]>
    {
        return this.createQueryBuilder('GET', '/api/dices')
            .getResult()
    }
    
    public async createDice(userId: number, worldId: number, computation: string, personageId?: number): Promise<void>
    {
        if(personageId === undefined) {
            return this.createQueryBuilder('POST', '/api/dices')
                .addData({
                    computation: computation,
                    launcher: '/api/users/' + userId,
                    world: '/api/worlds/' + worldId
                })
                .getOneOrNullResult()
        }else {
            return this.createQueryBuilder('POST', '/api/dices')
                .addData({
                    computation: computation,
                    launcher: '/api/users/' + userId,
                    world: '/api/worlds/' + worldId,
                    personage: '/api/personages/' + personageId
                })
                .getOneOrNullResult()
        }
    }

}