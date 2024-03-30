import { Item } from "../entity/item";
import { NumberOfAttribute } from "../entity/numberofattribute";
import { NumberOfPoint } from "../entity/numberofpoint";
import { Personage } from "../entity/personage";
import { Spell } from "../entity/spell";
import { NumberOfAttributeRepository } from "../repository/numberofattributeRepository";
import { NumberOfPointRepository } from "../repository/numberofpointRepository";
import { PersonageRepository } from "../repository/personageRepository";
import { ImageService } from "./imageService";

/**
 * @author Matthieu Pays <pays.matthieuic@gmail.com>
 * Service used by character sheet components to set up a CRUD on the world's characters
 */
export class PersonageService
{

    private personageRepository: PersonageRepository = new PersonageRepository();

    private numberOfAttributeRepository: NumberOfAttributeRepository = new NumberOfAttributeRepository();

    private numberOfPointRepository: NumberOfPointRepository = new NumberOfPointRepository();

    private imageService: ImageService = new ImageService();

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
        return this.imageService.uploadImage(personage.image).then((image) => {
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
            req.items.forEach((item: Item) => {
                item.itemPrefab = '/api/item_prefabs/' + item.itemPrefab.id;
            })
            req.spells.forEach((spell: Spell, index: number) => {
                req.spells[index] = '/api/spells/' + spell.id;
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
        let req: Personage = JSON.parse(JSON.stringify(personage));
        this.imageService.uploadImage(personage.image).then((image) => {
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
            req.items.forEach((item: Item, index: number) => {
                if(item.id){
                    req.items[index] = '/api/items/' + item.id;
                }else {
                    item.itemPrefab = '/api/item_prefabs/' + item.itemPrefab.id;
                }
            })
            req.spells.forEach((spell: Spell, index: number) => {
                req.spells[index] = '/api/spells/' + spell.id;
            })
            this.personageRepository.updatePersonagePartially(req);
        })
    }

}