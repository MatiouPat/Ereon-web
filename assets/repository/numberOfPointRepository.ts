import { NumberOfPoint } from "../entity/numberofpoint";
import { AbstractRepository } from "../utils/abstractRepository";

export class NumberOfPointRepository extends AbstractRepository
{
    public async updateNumberOfPointPartially(numberOfPoint: NumberOfPoint): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/number_of_points/' + numberOfPoint.id)
            .addData(numberOfPoint)
            .getOneOrNullResult()
    }

}