import { Music } from "../entity/music";
import { AbstractRepository } from "../utils/abstractRepository";

export class MusicRepository extends AbstractRepository
{
    public async findAllMusics(): Promise<Music[]>
    {
        return this.createQueryBuilder('GET', '/api/music')
            .getResult()
    }

}