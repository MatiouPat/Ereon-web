import axios from "axios";
import { MusicPlayer } from "../entity/musicplayer";

export class MusicPlayerRepository
{

    public async findMusicPlayerByWorld(worldId: number): Promise<MusicPlayer>
    {
        return axios({
            method: 'GET',
            url: '/api/music_players?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member'][0]
        })
    }

}