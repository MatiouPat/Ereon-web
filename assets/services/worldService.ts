import { World } from "../entity/world";
import { ConnectionRepository } from "../repository/connectionRepository";
import { WorldRepository } from "../repository/worldRepository";

export class WorldService
{

    private worldRepository: WorldRepository = new WorldRepository();

    private connectionRepository: ConnectionRepository = new ConnectionRepository();

    public async createWorld(world: World, userId: number): Promise<World>
    {
        return this.worldRepository.createWorld(world);
    }

}