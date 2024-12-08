import { Connection } from "../entity/connection";
import { Map } from "../entity/map";
import { ConnectionRepository } from "../repository/connectionRepository";
import { MapRepository } from "../repository/mapRepository";

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

    public async createMap(map: Map): Promise<void>
    {
        let req: Map = JSON.parse(JSON.stringify(map));
        req.connections.forEach((connection: Connection, key: number) => {
            this.connectionRepository.updateCurrentMap(connection.id, map.id);
            req.connections[key] = '/api/connections/' + connection.id
        })
        this.mapRepository.createMap(req);
    }

    public async deleteMap(mapId: number): Promise<void>
    {
        this.mapRepository.deleteMap(mapId);
    }

    public async updateMapPartially(map: Map): Promise<void>
    {
        let req: Map = JSON.parse(JSON.stringify(map));
        req.connections.forEach((connection: Connection, key: number) => {
            this.connectionRepository.updateCurrentMap(connection.id, map.id);
            req.connections[key] = '/api/connections/' + connection.id
        })
        this.mapRepository.updateMapPartially(req);
    }

}