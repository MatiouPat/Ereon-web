import { ArmorPrefab } from "../entity/armorprefab";
import { ArmorPrefabRepository } from "../repository/armorprefabRepository";

export class ArmorPrefabService
{

    private armorPrefabsRepository: ArmorPrefabRepository = new ArmorPrefabRepository();

    public async findArmorPrefabByWorld(worldId: number): Promise<ArmorPrefab[]>
    {
        return this.armorPrefabsRepository.findArmorPrefabByWorld(worldId);
    }

    public async createArmorPrefab(armorPrefab: ArmorPrefab): Promise<ArmorPrefab>
    {
        return this.armorPrefabsRepository.createArmorPrefab(armorPrefab);
    }

}