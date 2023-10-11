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

    public async findMapById(mapId: number): Promise<Map>
    {
        return this.mapRepository.findMapById(mapId);
    }

    public async updateMapPartially(map: Map, connections: number[]): Promise<void>
    {
        this.mapRepository.updateMapPartially(map);
        connections.forEach(connection => {
            this.connectionRepository.updateCurrentMap(connection, map.id);
        })
    }

}