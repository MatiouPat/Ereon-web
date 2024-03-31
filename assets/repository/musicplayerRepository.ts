import { MusicPlayer } from "../entity/musicplayer";
import { AbstractRepository } from "../utils/abstractRepository";

export class MusicPlayerRepository extends AbstractRepository
{
    public async findMusicPlayerByWorld(worldId: number): Promise<MusicPlayer>
    {
        return this.createQueryBuilder('GET', '/api/music_players?world.id=' + worldId)
            .getResult()[0]
    }

    public async setIsPlaying(musicPlayerId: number, isPlaying: boolean, currentTimePlay: number): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/music_players/' + musicPlayerId)
            .addData({
                isPlaying: isPlaying,
                currentTimePlay: Number(currentTimePlay)
            })
            .getOneOrNullResult()
    }

    public async setIsLooping(musicPlayerId: number, isLooping: boolean): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/music_players/' + musicPlayerId)
            .addData({
                isLooping: isLooping
            })
            .getOneOrNullResult()
    }

    public async setCurrentTimePlay(musicPlayerId: number, currentTimePlay: number): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/music_players/' + musicPlayerId)
            .addData({
                currentTimePlay: Number(currentTimePlay)
            })
            .getOneOrNullResult()
    }

    public async setCurrentMusic(musicPlayerId: number, musicId: number, currentTimePlay: number): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/music_players/' + musicPlayerId)
            .addData({
                currentMusic: 'api/music/' + musicId,
                currentTimePlay: Number(currentTimePlay)
            })
            .getOneOrNullResult()
    }
}