import axios from "axios";

export class UserParameterRepository
{

    public async updateGlobalVolume(userId: number, globalVolume: number): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/user_parameters/' + userId,
            data: {
                globalVolume: Number(globalVolume)
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        });
    }

    public async updateTheme(userId: number, isDarkTheme: boolean): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/user_parameters/' + userId,
            data: {
                isDarkTheme: Boolean(isDarkTheme)
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

}