import axios from "axios";
import { Attribute } from "../entity/attribute";

export class AttributeRepository
{

    public async findAttributeByWorld(worldId: number): Promise<Attribute[]>
    {
        return axios({
            method: 'GET',
            url: '/api/attributes?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

}