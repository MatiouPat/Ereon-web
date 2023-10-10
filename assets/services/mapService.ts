import { Map } from "../entity/map";
import { MapRepository } from "../repository/mapRepository";

export class MapService
{
    private mapRepository: MapRepository = new MapRepository();

    public async findAllMaps(): Promise<Map[]>
    {
        return this.mapRepository.findAllMaps();
    }

    public async findMapById(mapId: number): Promise<Map>
    {
        return this.mapRepository.findMapById(mapId);
    }

    public async updateMapPartially(map: Map, connections: string[]): Promise<void>
    {
        if(connections.length === 0) {
            this.mapRepository.updateMapPartially(map, undefined);
        }else {
            this.mapRepository.updateMapPartially(map, connections);
        }
    }

}