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

}