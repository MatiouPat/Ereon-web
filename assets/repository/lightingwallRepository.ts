import axios from "axios";
import { LightingWall } from "../entity/lightingwall";

export class LightingWallRepository
{

    public async createLightingWall(lightingWall: LightingWall): Promise<void>
    {
        axios({
            method: 'POST',
            url: '/api/lighting_walls',
            data: lightingWall,
            headers: {
                'Content-Type': 'application/json'
            }
        });
    }

    public async deleteLightingWall(lightingWallId: number): Promise<void>
    {
        axios({
            method: 'DELETE',
            url: '/api/lighting_walls/' + lightingWallId
        })
    }

}