import { Personage } from "../entity/personage";
import { AbstractRepository } from "../utils/abstractRepository";

export class PersonageRepository extends AbstractRepository
{
    public async findPersonagesByWorld(worldId: number): Promise<Personage[]>
    {
        return this.createQueryBuilder('GET', '/api/personages?world.id=' + worldId)
            .getResult()
    }

    public async findNonPlayerPersonagesByWorld(worldId: number): Promise<Personage[]>
    {
        return this.createQueryBuilder('GET', '/api/personages?exists[user]=false&world.id=' + worldId)
            .getResult()
    }

    public async findPersonagesByWorldAndByUser(worldId: number, userId: number): Promise<Personage[]>
    {
        return this.createQueryBuilder('GET', '/api/personages?world.id=' + worldId + '&user.id=' + userId)
            .getResult()
    }

    public async createPersonage(personage: Personage): Promise<Personage>
    {
        return this.createQueryBuilder('POST', '/api/personages')
            .addData(personage)
            .getOneOrNullResult()
    }

    public async deletePersonage(personageId: number): Promise<void>
    {
        return this.createQueryBuilder('DELETE', '/api/personages/' + personageId)
            .getOneOrNullResult()
    }

    public async updatePersonagePartially(personage: Personage): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/personages/' + personage.id)
            .addData(personage)
            .getOneOrNullResult()
    }

}