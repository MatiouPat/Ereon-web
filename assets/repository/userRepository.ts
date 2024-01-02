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

    public async updateUserPartially(user: User): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/users/' + user.id,
            data: user,
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

}