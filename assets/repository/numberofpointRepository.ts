import axios from "axios";
import { NumberOfPoint } from "../entity/numberofpoint";

export class NumberOfPointRepository
{

    public async updateNumberOfPointPartially(numberOfPoint: NumberOfPoint): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/number_of_points/' + numberOfPoint.id,
            data: numberOfPoint,
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

}