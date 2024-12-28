import {Music} from "../entity/music";
import {MusicRepository} from "../repository/musicRepository";

export class MusicService
{
    private musicRepository: MusicRepository = new MusicRepository();

    public async findAllMusics(): Promise<Music[]>
    {
        return this.musicRepository.findAllMusics();
    }
}