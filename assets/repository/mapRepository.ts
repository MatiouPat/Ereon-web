import { Map } from "../entity/map";
import { AbstractRepository } from "../utils/abstractRepository";

export class MapRepository extends AbstractRepository
{
    public async findAllMaps(): Promise<Map[]>
    {
        return this.createQueryBuilder('GET', '/api/maps')
            .getResult()
    }

    public async findMapsByWorld(worldId: number): Promise<Map[]>
    {
        return this.createQueryBuilder('GET', '/api/maps?world.id=' + worldId)
            .getResult()
    }

    public async findMapById(mapId: number): Promise<Map>
    {
        return this.createQueryBuilder('GET', '/api/maps/' + mapId)
            .getOneOrNullResult()
    }

    public async createMap(map: Map): Promise<void>
    {
        return this.createQueryBuilder('POST', '/api/maps')
            .addData(map)
            .getOneOrNullResult()
    }

    public async deleteMap(mapId: number): Promise<void>
    {
        return this.createQueryBuilder('DELETE', '/api/maps/' + mapId)
            .getOneOrNullResult()
    }

    public async updateMapPartially(map: Map): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/maps/' + map.id)
            .addData(map)
            .getOneOrNullResult()
    }

}