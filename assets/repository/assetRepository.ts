import axios from "axios";
import { Asset } from "../entity/asset";

export class AssetRepository
{

    public async findAllAssets(): Promise<Asset[]>
    {
        return axios({
            method: 'GET',
            url: '/api/assets'
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

    public async createAsset(asset: Asset): Promise<Asset>
    {
        return axios({
            method: 'POST',
            url: '/api/assets',
            data: asset,
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            return res.data
        })
    }

}