import axios from "axios";
import { ItemPrefab } from "../entity/itemprefab";

export class ItemPrefabRepository
{

    public async findItemPrefabByWorld(worldId: number): Promise<ItemPrefab[]>
    {
        return axios({
            method: 'GET',
            url: '/api/item_prefabs?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member'];
        })
    }

    public async createItemPrefab(itemPrefab: ItemPrefab): Promise<ItemPrefab>
    {
        return axios({
            method: 'POST',
            url: '/api/item_prefabs',
            data: itemPrefab,
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            return res.data;
        })
    }

}