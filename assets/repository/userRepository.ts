import axios from "axios";
import { User } from "../entity/user";

export class UserRepository
{

    public async findUserByWorldAndWhereIsNotGameMaster(worldId: number): Promise<User[]>
    {
        return axios({
            method: 'GET',
            url: '/api/users?connections.isGameMaster=false&connections.world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

}