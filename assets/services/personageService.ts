import axios from "axios";
import { Image } from "../entity/image";
import { NumberOfAttribute } from "../entity/numberofattribute";
import { NumberOfPoint } from "../entity/numberofpoint";
import { Personage } from "../entity/personage";
import { ImageRepository } from "../repository/imageRepository";
import { NumberOfAttributeRepository } from "../repository/numberofattributeRepository";
import { NumberOfPointRepository } from "../repository/numberofpointRepository";
import { PersonageRepository } from "../repository/personageRepository";

/**
 * 
 */
export class PersonageService
{

    private personageRepository: PersonageRepository = new PersonageRepository();

    private numberOfAttributeRepository: NumberOfAttributeRepository = new NumberOfAttributeRepository();

    private numberOfPointRepository: NumberOfPointRepository = new NumberOfPointRepository();

    private imageRepository: ImageRepository = new ImageRepository();

    /**
     * 
     * @returns 
     */
    public async findAllPersonages(): Promise<Personage[]>
    {
        return this.personageRepository.findAllPersonages().then((res) => {
            return res
        })
    }

    /**
     * 
     * @param personage 
     * @returns 
     */
    public async createPersonage(personage: Personage): Promise<Personage>
    {
        let req = JSON.parse(JSON.stringify(personage));
        return this.uploadImage(personage.image).then((image) => {
            personage.image = image;
            if (image.id) {
                req.image = 'api/images/' + image.id
            }
            req.numberOfAttributes?.forEach((numberofattribute: NumberOfAttribute) => {
                numberofattribute.attribute = numberofattribute.attribute['@id'];
            })
            req.numberOfPoints?.forEach((numberOfPoint: NumberOfPoint) => {
                numberOfPoint.point = numberOfPoint.point['@id'];
            })
            return this.personageRepository.createPersonage(req);
        })
    }

    /**
     * 
     * @param personageId 
     */
    public async deletePersonage(personageId: number): Promise<void>
    {
        this.personageRepository.deletePersonage(personageId);
    }

    /**
     * 
     * @param personage 
     */
    public async updatePersonagePartially(personage: Personage): Promise<void>
    {
        let req = JSON.parse(JSON.stringify(personage));
        this.uploadImage(personage.image).then((image) => {
            personage.image = image;
            if (image.id) {
                req.image = 'api/images/' + image.id
            }
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
        })
    }

    /**
     * 
     * @param image 
     */
    private async uploadImage(image: Image): Promise<Image>
    {
        if(!image.imageFile) {
            return image;
        }

        if(image.id) {
            return await this.imageRepository.updateImagePartially(image);
        }else {
            return await this.imageRepository.createImage(image);
        }
    }

}