import { Image } from "../entity/image";
import { NumberOfAttribute } from "../entity/numberofattribute";
import { NumberOfPoint } from "../entity/numberofpoint";
import { Personage } from "../entity/personage";
import { ImageRepository } from "../repository/imageRepository";
import { NumberOfAttributeRepository } from "../repository/numberofattributeRepository";
import { NumberOfPointRepository } from "../repository/numberofpointRepository";
import { PersonageRepository } from "../repository/personageRepository";

/**
 * @author Matthieu Pays <pays.matthieuic@gmail.com>
 * Service used by character sheet components to set up a CRUD on the world's characters
 */
export class PersonageService
{

    private personageRepository: PersonageRepository = new PersonageRepository();

    private numberOfAttributeRepository: NumberOfAttributeRepository = new NumberOfAttributeRepository();

    private numberOfPointRepository: NumberOfPointRepository = new NumberOfPointRepository();

    private imageRepository: ImageRepository = new ImageRepository();

    /**
     * Find all the characters in a world
     * @param worldId Id of the chosen world
     * @returns 
     */
    public async findPersonagesByWorld(worldId: number): Promise<Personage[]>
    {
        return this.personageRepository.findPersonagesByWorld(worldId).then((res) => {
            return res;
        })
    }

    /**
     * Find all a player's characters on the chosen world
     * @param worldId Id of the chosen world
     * @param userId Id of logged-in user
     * @returns 
     */
    public async findPersonagesByWorldAndByUser(worldId: number, userId: number): Promise<Personage[]>
    {
        return this.personageRepository.findPersonagesByWorldAndByUser(worldId, userId).then((res) => {
            return res;
        })
    }

    /**
     * Create a new character
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
            }else {
                req.image = undefined
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
     * Delete a character
     * @param personageId 
     */
    public async deletePersonage(personageId: number): Promise<void>
    {
        this.personageRepository.deletePersonage(personageId);
    }

    /**
     * Edit a character
     * @param personage 
     */
    public async updatePersonagePartially(personage: Personage): Promise<void>
    {
        let req = JSON.parse(JSON.stringify(personage));
        this.uploadImage(personage.image).then((image) => {
            personage.image = image;
            if (image.id) {
                req.image = 'api/images/' + image.id
            }else {
                req.image = undefined
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
     * Call the image service if an image is uploaded via the form in the character component.
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