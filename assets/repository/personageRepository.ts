import axios from "axios";
import { Personage } from "../entity/personage";
import { NumberOfAttribute } from "../entity/numberofattribute";

export class PersonageRepository
{

    public async findAllPersonages(): Promise<Personage[]>
    {
        return axios({
            method: 'GET',
            url: '/api/personages'
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

    public async createPersonage(personage: Personage): Promise<Personage>
    {
        return axios({
            method: 'POST',
            url: '/api/personages',
            data: personage,
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then((res) => {
            return res.data
        })
    }

    public async deletePersonage(personageId: number): Promise<void>
    {
        axios({
            method: 'DELETE',
            url: '/api/personages/' + personageId
        });
    }

    public async updatePersonagePartially(personage: Personage): Promise<void>
    {
        axios({
            method: 'POST',
            url: '/api/personages/' + personage.id,
            data: personage,
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

}