import { NumberOfAttribute } from "../entity/numberofattribute";
import { AbstractRepository } from "../utils/abstractRepository";

export class NumberOfAttributeRepository extends AbstractRepository
{
    public async updateNumberOfAttributePartially(numberOfAttribute: NumberOfAttribute): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/number_of_attributes/' + numberOfAttribute.id)
            .addData(numberOfAttribute)
            .getOneOrNullResult()
    }

}