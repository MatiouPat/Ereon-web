import { Asset } from "../entity/asset"
import { AssetRepository } from "../repository/assetRepository"
import { ImageService } from "./imageService";

export class AssetService
{

    private assetRepository: AssetRepository = new AssetRepository();

    private imageService: ImageService = new ImageService();
   
    public async findAllAssets(): Promise<Asset[]>
    {
        return this.assetRepository.findAllAssets();
    }

    public async createAsset(asset: Asset): Promise<Asset>
    {
        let req = JSON.parse(JSON.stringify(asset));
        return this.imageService.uploadImage(asset.image).then((image) => {
            asset.image = image;
            if (image.id) {
                req.image = 'api/images/' + image.id
            }else {
                req.image = undefined
            }
            return this.assetRepository.createAsset(req);
        })
    }

}