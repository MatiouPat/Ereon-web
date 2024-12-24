import { AbstractRepository } from "../utils/abstractRepository";

export class UserParameterRepository extends AbstractRepository
{
    public async updateGlobalVolume(userId: number, globalVolume: number): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/user_parameters/' + userId)
            .addData({
                globalVolume: Number(globalVolume)
            })
            .getOneOrNullResult()
    }

    public async updateTheme(userId: number, isDarkTheme: boolean): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/user_parameters/' + userId)
            .addData({
                isDarkTheme: Boolean(isDarkTheme)
            })
            .getOneOrNullResult()
    }

}