import { ItemPrefab } from "../entity/itemprefab";
import { AbstractRepository } from "../utils/abstractRepository";

export class ItemPrefabRepository extends AbstractRepository
{
    public async findItemPrefabByWorld(worldId: number): Promise<ItemPrefab[]>
    {
        return this.createQueryBuilder('GET', '/api/item_prefabs?world.id=' + worldId)
            .getResult()
    }

    public async createItemPrefab(itemPrefab: ItemPrefab): Promise<ItemPrefab>
    {
        return this.createQueryBuilder('POST', '/api/item_prefabs')
            .addData(itemPrefab)
            .getOneOrNullResult()
    }

}