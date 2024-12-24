import { defineStore } from "pinia";
import { UserParameter } from "../entity/userparameter";
import {MusicPlayer} from "../entity/musicplayer";
import {MusicPlayerService} from "../services/musicPlayerService";
import {UserParameterService} from "../services/userParameterService";
import {Music} from "../entity/music";

let userParameterService = new UserParameterService();
let musicPlayerService = new MusicPlayerService();

export const useMusicStore = defineStore('music', {
    state: () => ({
        userParameter: {} as UserParameter,
        musicPlayer: {} as MusicPlayer,
        currentMusic: {
            title: "" as string,
            link: "" as string,
            duration: 0 as number,
            displayedDuration: "" as string,
            currentTime: 0 as number,
            displayedCurrentTime: "" as string
        }
    }),
    getters: {
        getUserParameterId: (state) => {
            return state.userParameter.id;
        },
        getUserVolume: (state) => {
            return state.userParameter.globalVolume;
        },
        getIsLooping: (state) => {
            return state.musicPlayer.isLooping;
        },
        getIsPlaying: (state) => {
            return state.musicPlayer.isPlaying;
        },
        getMusicPlayerId: (state) => {
            return state.musicPlayer.id;
        },
        getCurrentMusic: (state) => {
            return state.currentMusic;
        }
    },
    actions: {
        async setUserParameter(userParameter: UserParameter): Promise<void>
        {
            this.userParameter = userParameter;
        },
        async setUserVolume(userVolume: number): Promise<void>
        {
            this.userParameter.globalVolume = userVolume;
            return userParameterService.updateGlobalVolume(this.getUserParameterId, userVolume)
        },
        async setMusicPlayer(musicPlayer: MusicPlayer): Promise<void>
        {
            this.musicPlayer = musicPlayer;
        },
        async setIsPlaying(isPlaying: boolean): Promise<void>
        {
            this.musicPlayer.isPlaying = isPlaying;
            return musicPlayerService.setIsPlaying(this.musicPlayer.id, isPlaying, this.currentMusic.currentTime);
        },
        async setIsLooping(isLooping: boolean): Promise<void>
        {
            this.musicPlayer.isLooping = isLooping;
            return musicPlayerService.setIsLooping(this.musicPlayer.id, isLooping);
        },
        async setCurrentMusic(currentMusic: Music): Promise<void>
        {
            this.currentMusic.title = currentMusic.title;
            this.currentMusic.link = currentMusic.link;
            this.currentMusic.currentTime = 0;
            return musicPlayerService.setCurrentMusic(this.musicPlayer.id, currentMusic.id, this.currentMusic.currentTime);
        },
        async setCurrentMusicCurrentTime(currentTime: number): Promise<void>
        {
            this.currentMusic.currentTime = currentTime;
            let minutes = Math.floor(this.currentMusic.currentTime / 60);
            let seconds = Math.floor(this.currentMusic.currentTime % 60);
            this.currentMusic.displayedCurrentTime = minutes + ":" + String(seconds).padStart(2, '0');
        },
        async setCurrentMusicDuration(duration: number): Promise<void>
        {
            this.currentMusic.duration = duration;
            let minutes = Math.floor(this.currentMusic.duration / 60);
            let seconds = Math.floor(this.currentMusic.duration % 60);
            this.currentMusic.displayedDuration = minutes + ":" + String(seconds).padStart(2, '0');
        }
    }
})