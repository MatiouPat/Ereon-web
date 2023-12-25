import { ItemPrefab } from "../entity/itemprefab";
import { ItemPrefabRepository } from "../repository/itemprefabRepository";

export class ItemPrefabService
{

    private itemPrefabsRepository: ItemPrefabRepository = new ItemPrefabRepository();

    public async findItemPrefabByWorld(worldId: number): Promise<ItemPrefab[]>
    {
        let res: ItemPrefab[] = [];
        return this.itemPrefabsRepository.findItemPrefabByWorld(worldId).then(itemPrefabs => {
            itemPrefabs.forEach(itemPrefab => {
                if(itemPrefab["@type"] === 'ItemPrefab') {
                    res.push(itemPrefab);
                }
            })
            return res;
        })
    }

    public async createItemPrefab(itemPrefab: ItemPrefab): Promise<ItemPrefab>
    {
        return this.itemPrefabsRepository.createItemPrefab(itemPrefab);
    }

}