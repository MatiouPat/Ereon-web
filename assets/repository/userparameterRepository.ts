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

}