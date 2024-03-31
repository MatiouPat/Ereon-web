import { WeaponPrefab } from "../entity/weaponprefab";
import { AbstractRepository } from "../utils/abstractRepository";

export class WeaponPrefabRepository extends AbstractRepository
{
    public async findWeaponPrefabByWorld(worldId: number): Promise<WeaponPrefab[]>
    {
        return this.createQueryBuilder('GET', '/api/weapon_prefabs?world.id=' + worldId)
            .getResult()
    }

    public async createWeaponPrefab(weaponPrefab: WeaponPrefab): Promise<WeaponPrefab>
    {
        return this.createQueryBuilder('POST', '/api/weapon_prefabs')
            .addData(weaponPrefab)
            .getOneOrNullResult()
    }

}