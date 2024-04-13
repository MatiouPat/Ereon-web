import { Connection } from "../entity/connection";
import { AbstractRepository } from "../utils/abstractRepository";

export class ConnectionRepository extends AbstractRepository
{
    public async findAllConnectionsByWorld(worldId: number): Promise<Connection[]>
    {
        return this.createQueryBuilder('GET', '/api/connections?world.id=' + worldId)
            .getResult()
    }

    public async findAllRecentConnectionByWorld(worldId: number, lastConnectionAt: string): Promise<Connection[]>
    {
        return this.createQueryBuilder('GET', '/api/connections?world.id=' + worldId + "&lastConnectionAt[after]=" + lastConnectionAt)
            .getResult()
    }

    public async findPlayerByWorldAndWhereIsNotGameMaster(worldId: number): Promise<Connection[]>
    {
        return this.createQueryBuilder('GET', '/api/connections?isGameMaster=false&world.id=' + worldId)
            .getResult()
    }

    public async updateCurrentMap(connectionId: number, mapId: number): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/connections/' + connectionId)
            .addData({
                currentMap: "/api/maps/" + mapId
            })
            .getOneOrNullResult()
    }

    public async updateLastConnectionAt(connectionId: number, lastConnectionAt: string): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/connections/' + connectionId)
            .addData({
                lastConnectionAt: lastConnectionAt
            })
            .getOneOrNullResult()
    }

}