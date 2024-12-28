import { LightingWall } from "../entity/lightingwall";
import { LightingWallRepository } from "../repository/lightingWallRepository";

export class LightingWallService
{

    private lightingWallRepository: LightingWallRepository = new LightingWallRepository();

    public async createLightingWall(lightingWall: LightingWall): Promise<void>
    {
        this.lightingWallRepository.createLightingWall(lightingWall);
    }

    public async deleteAllLightingWallsByMap(lightingWalls: LightingWall[]): Promise<void>
    {
        lightingWalls.forEach((lightingWall) => {
            this.lightingWallRepository.deleteLightingWall(lightingWall.id!)
        })
    }

}