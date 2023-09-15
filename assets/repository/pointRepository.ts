import axios from "axios";
import { Point } from "../entity/point";

export class PointRepository
{

    public async findPointByWorld(worldId: number): Promise<Point[]>
    {
        return axios({
            method: 'GET',
            url: '/api/points?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

}