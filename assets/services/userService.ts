import { User } from "../entity/user";
import { UserRepository } from "../repository/userRepository";

export class UserService
{

    private userRepository: UserRepository = new UserRepository();

    public async updateUserPartially(user: User): Promise<void>
    {
        user.connections = undefined;
        this.userRepository.updateUserPartially(user);
    }

}