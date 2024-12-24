import {MusicPlayer} from "../entity/musicplayer";
import {MusicPlayerRepository} from "../repository/musicPlayerRepository";

export class MusicPlayerService
{
    private musicPlayerRepository: MusicPlayerRepository = new MusicPlayerRepository();

    public async findMusicPlayerByWorld(worldId: number): Promise<MusicPlayer>
    {
        return this.musicPlayerRepository.findMusicPlayerByWorld(worldId);
    }

    public async setIsPlaying(musicPlayerId: number, isPlaying: boolean, currentTimePlay: number): Promise<void>
    {
        return this.musicPlayerRepository.setIsPlaying(musicPlayerId, isPlaying, currentTimePlay);
    }

    public async setIsLooping(musicPlayerId: number, isLooping: boolean): Promise<void>
    {
        return this.musicPlayerRepository.setIsLooping(musicPlayerId, isLooping);
    }

    public async setCurrentTimePlay(musicPlayerId: number, currentTimePlay: number): Promise<void>
    {
        return this.musicPlayerRepository.setCurrentTimePlay(musicPlayerId, currentTimePlay);
    }

    public async setCurrentMusic(musicPlayerId: number, musicId: number, currentTimePlay: number): Promise<void>
    {
        return this.musicPlayerRepository.setCurrentMusic(musicPlayerId, musicId, currentTimePlay);
    }
}