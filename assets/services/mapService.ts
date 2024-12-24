import { Connection } from "../entity/connection";
import { Map } from "../entity/map";
import { ConnectionRepository } from "../repository/connectionRepository";
import { MapRepository } from "../repository/mapRepository";
import {Token} from "../entity/token";

type ConnectionCheckField = {
    connection: Connection,
    isOnMap: boolean,
    currentIsOnMap: boolean
}

export class MapService
{
    private mapRepository: MapRepository = new MapRepository();

    private connectionRepository: ConnectionRepository = new ConnectionRepository();

    public async findAllMaps(): Promise<Map[]>
    {
        return this.mapRepository.findAllMaps();
    }

    public async findMapsByWorld(worldId: number): Promise<Map[]>
    {
        return this.mapRepository.findMapsByWorld(worldId);
    }


    public async findMapById(mapId: number): Promise<Map>
    {
        return this.mapRepository.findMapById(mapId);
    }

    public async createMap(map: Map, playerConnections?: ConnectionCheckField[]): Promise<void>
    {
        /*let req: Map = structuredClone(map);
        req.connections.forEach((connection: Connection, key: number) => {
            this.connectionRepository.updateCurrentMap(connection.id, map.id);
            req.connections[key] = '/api/connections/' + connection.id
        })
        return this.mapRepository.createMap(req);*/
    }

    public async deleteMap(mapId: number): Promise<void>
    {
        return this.mapRepository.deleteMap(mapId);
    }

    public async updateMapPartially(map: Map, playerConnections?: ConnectionCheckField[]): Promise<void>
    {
        let req: Map = structuredClone(map);
        req.connections.forEach((connection: Connection, key: number) => {
            req.connections[key] = '/api/connections/' + connection.id
        })
        playerConnections.forEach((playerConnection: ConnectionCheckField) => {
            if(playerConnection.currentIsOnMap && !playerConnection.isOnMap) {
                req.connections.push('/api/connections/' + playerConnection.connection.id)
            }
        })
        req.tokens.forEach((token: Token, key: number) => {
            req.tokens[key] = '/api/tokens/' + token.id
        })
        return this.mapRepository.updateMapPartially(req);
    }

}