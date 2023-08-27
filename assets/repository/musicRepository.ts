import axios from "axios";
import { Music } from "../entity/music";

export class MusicRepository
{

    public async findAllMusics(): Promise<Music[]>
    {
        return axios({
            method: 'GET',
            url: '/api/music'
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

}