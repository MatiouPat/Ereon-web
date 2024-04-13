import { LightingWall } from "../entity/lightingwall";
import { AbstractRepository } from "../utils/abstractRepository";

export class LightingWallRepository extends AbstractRepository
{
    public async createLightingWall(lightingWall: LightingWall): Promise<void>
    {
        return this.createQueryBuilder('POST', '/api/lighting_walls')
            .addData(lightingWall)
            .getOneOrNullResult()
    }

    public async deleteLightingWall(lightingWallId: number): Promise<void>
    {
        return this.createQueryBuilder('DELETE', '/api/lighting_walls/' + lightingWallId)
            .getOneOrNullResult()
    }

}