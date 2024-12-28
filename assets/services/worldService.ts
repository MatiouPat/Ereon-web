import { World } from "../entity/world";
import { WorldRepository } from "../repository/worldRepository";
import {ImageService} from "./imageService";

export class WorldService
{

    private worldRepository: WorldRepository = new WorldRepository();

    private imageService: ImageService = new ImageService();

    public async createWorld(world: World, attributes: any[]): Promise<World>
    {
        let req = structuredClone(world);
        req.skills.pop();
        req.skills.forEach(skill => {
            skill.id = undefined;
            skill.attribute.acronym = attributes[skill.attribute.acronym-1].value;
        })
        req.attributes.pop();
        req.attributes.forEach(attribute => {
            attribute.id = undefined;
        })

        if (world.image) {
            return this.imageService.uploadImage(world.image).then((image) => {
                world.image = image;
                if (image.id) {
                    req.image = 'api/images/' + image.id
                }else {
                    req.image = undefined
                }
                return this.worldRepository.createWorld(req);
            });
        }else {
            return this.worldRepository.createWorld(req);
        }

    }

    public async findWorldById(worldId: number): Promise<World>
    {
        return this.worldRepository.findWorldById(worldId);
    }

    public async findWorldByUser(userId: number): Promise<World[]>
    {
        return this.worldRepository.findWorldByUser(userId);
    }

}