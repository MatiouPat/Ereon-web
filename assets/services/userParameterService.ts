import {UserParameterRepository} from "../repository/userParameterRepository";

export class UserParameterService
{
    private userParameterRepository: UserParameterRepository = new UserParameterRepository();

    public async updateGlobalVolume(userId: number, globalVolume: number): Promise<void>
    {
        return this.userParameterRepository.updateGlobalVolume(userId, globalVolume);
    }

    public async updateTheme(userId: number, isDarkTheme: boolean): Promise<void>
    {
        return this.userParameterRepository.updateTheme(userId, isDarkTheme);
    }
}