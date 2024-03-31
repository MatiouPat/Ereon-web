import { User } from "../entity/user";
import { AbstractRepository } from "../utils/abstractRepository";

export class UserRepository extends AbstractRepository
{
    public async findUserByWorldAndWhereIsNotGameMaster(worldId: number): Promise<User[]>
    {
        return this.createQueryBuilder('GET', '/api/users?connections.isGameMaster=false&connections.world.id=' + worldId)
            .getResult()
    }

    public async updateUserPartially(user: User): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/users/' + user.id)
            .addData(user)
            .getOneOrNullResult()
    }

}