import { Asset } from "../entity/asset";
import { AbstractRepository } from "../utils/abstractRepository";

export class AssetRepository extends AbstractRepository
{
    public async findAllAssets(): Promise<Asset[]>
    {
        return this.createQueryBuilder('GET', '/api/assets')
            .getResult()
    }

    public async createAsset(asset: Asset): Promise<Asset>
    {
        return this.createQueryBuilder('POST', '/api/assets')
            .addData(asset)
            .getOneOrNullResult()
    }

}