import axios from "axios";
import { ArmorPrefab } from "../entity/armorprefab";

export class ArmorPrefabRepository
{

    public async findArmorPrefabByWorld(worldId: number): Promise<ArmorPrefab[]>
    {
        return axios({
            method: 'GET',
            url: '/api/armor_prefabs?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member'];
        })
    }

    public async createArmorPrefab(armorPrefab: ArmorPrefab): Promise<ArmorPrefab>
    {
        return axios({
            method: 'POST',
            url: '/api/armor_prefabs',
            data: armorPrefab,
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            return res.data;
        })
    }

}