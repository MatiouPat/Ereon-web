import axios from "axios";
import { WeaponPrefab } from "../entity/weaponprefab";

export class WeaponPrefabRepository
{

    public async findWeaponPrefabByWorld(worldId: number): Promise<WeaponPrefab[]>
    {
        return axios({
            method: 'GET',
            url: '/api/weapon_prefabs?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member'];
        })
    }

    public async createWeaponPrefab(weaponPrefab: WeaponPrefab): Promise<WeaponPrefab>
    {
        return axios({
            method: 'POST',
            url: '/api/weapon_prefabs',
            data: weaponPrefab,
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            return res.data;
        })
    }

}