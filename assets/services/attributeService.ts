import { Attribute } from "../entity/attribute";
import { AttributeRepository } from "../repository/attributeRepository";

export class AttributeService
{

    private attributeRepository: AttributeRepository = new AttributeRepository();

    public async findAttributeByWorld(worldId: number): Promise<Attribute[]>
    {
        return this.attributeRepository.findAttributeByWorld(worldId);
    }

}