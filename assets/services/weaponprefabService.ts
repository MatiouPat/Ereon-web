import { WeaponPrefab } from "../entity/weaponprefab";
import { WeaponPrefabRepository } from "../repository/weaponprefabRepository";

export class WeaponPrefabService
{

    private weaponPrefabsRepository: WeaponPrefabRepository = new WeaponPrefabRepository();

    public async findWeaponPrefabByWorld(worldId: number): Promise<WeaponPrefab[]>
    {
        return this.weaponPrefabsRepository.findWeaponPrefabByWorld(worldId);
    }

    public async createWeaponPrefab(weaponPrefab: WeaponPrefab): Promise<WeaponPrefab>
    {
        return this.weaponPrefabsRepository.createWeaponPrefab(weaponPrefab);
    }
    
}