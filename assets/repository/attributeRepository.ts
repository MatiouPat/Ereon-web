import { Attribute } from "../entity/attribute";
import { AbstractRepository } from "../utils/abstractRepository";

export class AttributeRepository extends AbstractRepository
{
    public async findAttributeByWorld(worldId: number): Promise<Attribute[]>
    {
        return this.createQueryBuilder('GET', '/api/attributes?world.id=' + worldId)
            .getResult()
    }

}