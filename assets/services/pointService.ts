import { Point } from "../entity/point"
import { PointRepository } from "../repository/pointRepository"

export class PointService
{
    private pointRepository: PointRepository = new PointRepository();

    public async findPointByWorld(worldId: number): Promise<Point[]>
    {
        return this.pointRepository.findPointByWorld(worldId);
    }

}