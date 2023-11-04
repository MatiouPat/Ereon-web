import { DiceRepository } from "../repository/diceRepository";

export class DiceService
{

    private diceRepository: DiceRepository = new DiceRepository();

    public async createDice(userId: number, worldId: number, computation: string, personageId: number): Promise<void>
    {
        if(personageId === 0) {
            this.diceRepository.createDice(userId, worldId, computation);
        }else {
            this.diceRepository.createDice(userId, worldId, computation, personageId);
        }
    }

}