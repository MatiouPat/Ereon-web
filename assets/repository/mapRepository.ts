import axios from "axios";
import { Map } from "../entity/map";
import { Connection } from "../entity/connection";

export class MapRepository
{

    public async findAllMaps(): Promise<Map[]>
    {
        return axios({
            method: 'GET',
            url: '/api/maps',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

    public async findMapById(mapId: number): Promise<Map>
    {
        return axios({
            method: 'GET',
            url: 'api/maps/' + mapId
        })
        .then(res => {
            return res.data
        })
    }

    public async createMap(map: Map): Promise<void>
    {
        return axios({
            method: 'POST',
            url: 'api/maps',
            data: map,
            headers: {
                'Content-Type': 'application/json'
            }
        })
    }

    public async updateMapPartially(map: Map): Promise<void>
    {
        return axios({
            method: 'PATCH',
            url: 'api/maps/' + map.id,
            data: map,
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

}