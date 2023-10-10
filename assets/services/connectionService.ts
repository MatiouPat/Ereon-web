import { Connection } from "../entity/connection"
import { ConnectionRepository } from "../repository/connectionRepository"

export class ConnectionService
{

    private connectionRepository = new ConnectionRepository();

    public async findAllConnectionsByWorld(worldId: number): Promise<Connection[]>
    {
        return this.connectionRepository.findAllConnectionsByWorld(worldId);
    }

    public async findAllRecentConnectionByWorld(worldId: number, lastConnectionAt: string): Promise<Connection[]>
    {
        return this.connectionRepository.findAllRecentConnectionByWorld(worldId, lastConnectionAt);
    }

    public async findPlayerByWorldAndWhereIsNotGameMaster(worldId: number): Promise<Connection[]>
    {
        return this.connectionRepository.findPlayerByWorldAndWhereIsNotGameMaster(worldId);
    }

    public async updateCurrentMap(connectionId: number, mapId: number): Promise<void>
    {
        this.connectionRepository.updateCurrentMap(connectionId, mapId);
    }

    public async updateLastConnectionAt(connectionId: number, lastConnectionAt: string): Promise<void>
    {
        this.connectionRepository.updateLastConnectionAt(connectionId, lastConnectionAt);
    }

}