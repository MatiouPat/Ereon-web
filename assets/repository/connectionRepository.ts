import axios from "axios";
import { Connection } from "../entity/connection";

export class ConnectionRepository
{

    public async findConnectionByWorld(worldId: number): Promise<Connection[]>
    {
        return axios({
            method: 'GET',
            url: '/api/connections?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

}