import { NumberOfAttribute } from "../entity/numberofattribute";
import { NumberOfPoint } from "../entity/numberofpoint";
import { Personage } from "../entity/personage";
import { NumberOfAttributeRepository } from "../repository/numberofattributeRepository";
import { NumberOfPointRepository } from "../repository/numberofpointRepository";
import { PersonageRepository } from "../repository/personageRepository";

export class PersonageService
{

    private personageRepository: PersonageRepository = new PersonageRepository();

    private numberOfAttributeRepository: NumberOfAttributeRepository = new NumberOfAttributeRepository();

    private numberOfPointRepository: NumberOfPointRepository = new NumberOfPointRepository();

    public async findAllPersonages(): Promise<Personage[]>
    {
        return this.personageRepository.findAllPersonages().then((res) => {
            return res
        })
    }

    public async createPersonage(personage: Personage): Promise<Personage>
    {
        let req = JSON.parse(JSON.stringify(personage));
        req.numberOfAttributes?.forEach((numberofattribute: NumberOfAttribute) => {
            numberofattribute.attribute = numberofattribute.attribute['@id'];
        })
        req.numberOfPoints?.forEach((numberOfPoint: NumberOfPoint) => {
            numberOfPoint.point = numberOfPoint.point['@id'];
        })
        return this.personageRepository.createPersonage(req);
    }

    public async deletePersonage(personageId: number): Promise<void>
    {
        this.personageRepository.deletePersonage(personageId);
    }

    public async updatePersonagePartially(personage: Personage): Promise<void>
    {
        let req = JSON.parse(JSON.stringify(personage));
        req.imageFile = personage.imageFile;
        req.numberOfAttributes?.forEach((numberOfAttribute: NumberOfAttribute, index: number) => {
            numberOfAttribute.attribute = numberOfAttribute.attribute['@id'];
            this.numberOfAttributeRepository.updateNumberOfAttributePartially(numberOfAttribute);
            req.numberOfAttributes[index] = numberOfAttribute['@id'];
        })
        req.numberOfPoints?.forEach((numberOfPoint: NumberOfPoint, index: number) => {
            numberOfPoint.point = numberOfPoint.point['@id'];
            this.numberOfPointRepository.updateNumberOfPointPartially(numberOfPoint);
            req.numberOfPoints[index] = numberOfPoint['@id'];
        })
        this.personageRepository.updatePersonagePartially(req);
    }

}