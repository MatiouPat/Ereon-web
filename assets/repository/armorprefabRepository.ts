import { ArmorPrefab } from "../entity/armorprefab";
import { AbstractRepository } from "../utils/abstractRepository";

export class ArmorPrefabRepository extends AbstractRepository
{
    public async findArmorPrefabByWorld(worldId: number): Promise<ArmorPrefab[]>
    {
        return this.createQueryBuilder('GET', '/api/armor_prefabs?world.id=' + worldId)
            .getResult()
    }

    public async createArmorPrefab(armorPrefab: ArmorPrefab): Promise<ArmorPrefab>
    {
        return this.createQueryBuilder('POST', '/api/armor_prefabs')
            .addData(armorPrefab)
            .getOneOrNullResult()
    }

}