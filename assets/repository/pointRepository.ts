import { Point } from "../entity/point";
import { AbstractRepository } from "../utils/abstractRepository";

export class PointRepository extends AbstractRepository
{
    public async findPointByWorld(worldId: number): Promise<Point[]>
    {
        return this.createQueryBuilder('GET', '/api/points?world.id=' + worldId)
            .getResult()
    }

}