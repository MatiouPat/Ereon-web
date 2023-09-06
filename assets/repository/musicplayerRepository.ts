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

    public async setIsPlaying(musicPlayerId: number, isPlaying: boolean, currentTimePlay: number): Promise<void>
    {
        return axios({
            method: 'PATCH',
            url: '/api/music_players/' + musicPlayerId,
            data: {
                isPlaying: isPlaying,
                currentTimePlay: Number(currentTimePlay)
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

    public async setIsLooping(musicPlayerId: number, isLooping: boolean): Promise<void>
    {
        return axios({
            method: 'PATCH',
            url: '/api/music_players/' + musicPlayerId,
            data: {
                isLooping: isLooping
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

    public async setCurrentTimePlay(musicPlayerId: number, currentTimePlay: number): Promise<void>
    {
        return axios({
            method: 'PATCH',
            url: '/api/music_players/' + musicPlayerId,
            data: {
                currentTimePlay: Number(currentTimePlay)
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }

    public async setCurrentMusic(musicPlayerId: number, musicId: number, currentTimePlay: number): Promise<void>
    {
        return axios({
            method: 'PATCH',
            url: '/api/music_players/' + musicPlayerId,
            data: {
                currentMusic: 'api/music/' + musicId,
                currentTimePlay: Number(currentTimePlay)
            },
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }
}