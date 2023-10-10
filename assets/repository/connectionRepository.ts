import axios from "axios";
import { Connection } from "../entity/connection";

export class ConnectionRepository
{

    public async findAllConnectionsByWorld(worldId: number): Promise<Connection[]>
    {
        return axios({
            method: 'GET',
            url: '/api/connections?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member'];
        })
    }

    public async findAllRecentConnectionByWorld(worldId: number, lastConnectionAt: string): Promise<Connection[]>
    {
        return axios({
            method: 'GET',
            url: '/api/connections?world.id=' + worldId + "&lastConnectionAt[after]=" + lastConnectionAt
        })
        .then(response => {
            return response.data['hydra:member'];
        })
    }

    public async findPlayerByWorldAndWhereIsNotGameMaster(worldId: number): Promise<Connection[]>
    {
        return axios({
            method: 'GET',
            url: '/api/connections?isGameMaster=false&world.id=' + worldId
        })
        .then(response => {
            return response.data['hydra:member'];
        })
    }

    public async updateCurrentMap(connectionId: number, mapId: number): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/connections/' + connectionId,
            data: {
                currentMap: "/api/maps/" + mapId
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        });
    }

    public async updateLastConnectionAt(connectionId: number, lastConnectionAt: string): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/connections/' + connectionId,
            data: {
                lastConnectionAt: lastConnectionAt
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        });
    }

}