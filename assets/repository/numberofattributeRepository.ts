import axios from "axios";
import { NumberOfAttribute } from "../entity/numberofattribute";

export class NumberOfAttributeRepository
{

    public async updateNumberOfAttributePartially(numberOfAttribute: NumberOfAttribute): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/number_of_attributes/' + numberOfAttribute.id,
            data: numberOfAttribute,
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

}