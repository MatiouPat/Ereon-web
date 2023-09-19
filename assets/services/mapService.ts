import { Map } from "../entity/map";
import { MapRepository } from "../repository/mapRepository";

export class MapService
{

    private mapRepository: MapRepository = new MapRepository();

    public async findMapById(mapId: number): Promise<Map>
    {
        return this.mapRepository.findMapById(mapId);
    }

}