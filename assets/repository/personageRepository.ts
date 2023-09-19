import axios from "axios";
import { Personage } from "../entity/personage";

export class PersonageRepository
{

    public async findPersonagesByWorld(worldId: number): Promise<Personage[]>
    {
        return axios({
            method: 'GET',
            url: '/api/personages?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

    public async findNonPlayerPersonagesByWorld(worldId: number): Promise<Personage[]>
    {
        return axios({
            method: 'GET',
            url: '/api/personages?exists[user]=false&world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

    public async findPersonagesByWorldAndByUser(worldId: number, userId: number): Promise<Personage[]>
    {
        return axios({
            method: 'GET',
            url: '/api/personages?world.id=' + worldId + '&user.id=' + userId
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
                'Content-Type': 'application/json'
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
            method: 'PATCH',
            url: '/api/personages/' + personage.id,
            data: personage,
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        });
    }

}