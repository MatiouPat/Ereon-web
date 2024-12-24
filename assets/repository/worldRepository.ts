import { World } from "../entity/world";
import { AbstractRepository } from "../utils/abstractRepository";

export class WorldRepository extends AbstractRepository
{

    public async createWorld(world: World): Promise<World>
    {
        return this.createQueryBuilder('POST', '/api/worlds')
            .addData(world)
            .getOneOrNullResult()
    }

    public async findWorldById(worldId: number): Promise<World>
    {
        return this.createQueryBuilder('GET', '/api/worlds/' + worldId)
            .getOneOrNullResult()
    }

    public async findWorldByUser(userId: number): Promise<World[]>
    {
        return this.createQueryBuilder('GET', '/api/worlds?connections.user.id=' + userId)
            .getResult()
    }

}