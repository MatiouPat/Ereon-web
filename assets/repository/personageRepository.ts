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

    public async updatePersonagePartially(personage: Personage): Promise<void>
    {
        let numberofattributes = [] as NumberOfAttribute[]
        let saveNumberOfAttributes = personage.numberOfAttributes;
        personage.numberOfAttributes?.forEach((numberofattribute: NumberOfAttribute) => {
            numberofattributes.push({
                id: numberofattribute.id,
                value: numberofattribute.value,
                attribute: numberofattribute.attribute['@id']
            })
        })
        personage.numberOfAttributes = numberofattributes
        axios({
            method: 'PATCH',
            url: '/api/personages/' + personage.id,
            data: {
                name: personage.name,
                race: personage.race,
                alignment: personage.alignment,
                class: personage.class,
                inventory: personage.inventory,
                numberOfAttributes: personage.numberOfAttributes
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
        personage.numberOfAttributes = saveNumberOfAttributes;
    }

}