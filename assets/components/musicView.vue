<template>
    <div class="music-box">
        <div class="music-informations" v-if="isGameMaster">
            <h2>Musique</h2>
            <span class="music-name">{{ getCurrentMusic.title }}</span>
            <div class="music-timeline">
                <span>{{ getCurrentMusic.displayedCurrentTime }}</span>
                <input type="range" min="0" v-model="getCurrentMusic.currentTime" :max="getCurrentMusic.duration" @input="changeCurrentTime">
                <span>{{ getCurrentMusic.displayedDuration }}</span>
            </div>
            <div class="music-controls">
                <img :src="getIsDarkTheme ? '/build/images/icons/skip_previous_white.svg' : '/build/images/icons/skip_previous_black.svg'" alt="Previous">
                <img v-if="!getIsPlaying" :src="getIsDarkTheme ? '/build/images/icons/play_white.svg' : '/build/images/icons/play_black.svg'" alt="Play" @click="play">
                <img v-if="getIsPlaying" :src="getIsDarkTheme ? '/build/images/icons/pause_white.svg' : '/build/images/icons/pause_black.svg'" alt="Pause" @click="pause">
                <img :src="getIsDarkTheme ? '/build/images/icons/skip_next_white.svg' : '/build/images/icons/skip_next_black.svg'" alt="Next">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" :fill="getIsDarkTheme ? '#FFFFFF' : '#1F262D'" class="music-controls-loop" :class="{ active: getIsLooping }"  @click="loop"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
            </div>
        </div>
        <div class="music-informations isDisable" v-else>
            <h2>Musique</h2>
            <span class="music-name">{{ getCurrentMusic.title }}</span>
            <div class="music-timeline">
                <span>{{ getCurrentMusic.displayedCurrentTime }}</span>
                <input type="range" min="0" v-model="getCurrentMusic.currentTime" disabled :max="getCurrentMusic.duration">
                <span>{{ getCurrentMusic.displayedDuration }}</span>
            </div>
            <div class="music-controls">
                <img :src="getIsDarkTheme ? '/build/images/icons/skip_previous_white.svg' : '/build/images/icons/skip_previous_black.svg'" alt="Previous">
                <img v-if="!getIsPlaying" :src="getIsDarkTheme ? '/build/images/icons/play_white.svg' : '/build/images/icons/play_black.svg'" alt="Play">
                <img v-if="getIsPlaying" :src="getIsDarkTheme ? '/build/images/icons/pause_white.svg' : '/build/images/icons/pause_black.svg'" alt="Pause">
                <img :src="getIsDarkTheme ? '/build/images/icons/skip_next_white.svg' : '/build/images/icons/skip_next_black.svg'" alt="Next">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" :fill="getIsDarkTheme ? '#FFFFFF' : '#1F262D'" class="music-controls-loop" :class="{ active: getIsLooping }"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
            </div>
        </div>
        <audio @volumechange="changeVolume" @timeupdate="updateCurrentTime" @loadeddata="updateDuration" ref="musicAudio" :src="getCurrentMusic.link != '' ? 'uploads/musics/' + getCurrentMusic.link : ''" :loop="getIsLooping"></audio>
        <table class="music-list" :class="{isDisable: !isGameMaster}">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                </tr>
            </thead>
            <tbody v-if="isGameMaster">
                <tr @click="changeMusic(index)" v-for="(music, index) in musics" :key="music.id">
                    <td>{{ index+1 }}</td>
                    <td>{{ music.title }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(music, index) in musics" :key="music.id">
                    <td>{{ index+1 }}</td>
                    <td>{{ music.title }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script lang="ts">
import { defineComponent, inject } from 'vue';
import { Music } from '../entity/music';
import {mapActions, mapState} from 'pinia';
import { useUserStore } from '../store/user';
import { useMusicStore } from '../store/music';
import {useWorldStore} from "../store/world";
import {MusicService} from "../services/musicService";
import {MusicPlayerService} from "../services/musicPlayerService";
  
    export default defineComponent({
        data() {
            return {
                emitter: inject('emitter') as any,
                musicService: new MusicService() as MusicService,
                musicPlayerService: new MusicPlayerService() as MusicPlayerService,
                musics: [] as Music[]
            }
        },
        computed: {
            ...mapState(useUserStore, [
                "isGameMaster",
                "getUserId",
                "getIsDarkTheme"
            ]),
            ...mapState(useMusicStore, [
                "getUserVolume",
                "getIsLooping",
                "getIsPlaying",
                "getMusicPlayerId",
                "getCurrentMusic"
            ]),
            ...mapState(useWorldStore, [
                "getWorld"
            ])
        },
        methods: {
            ...mapActions(useMusicStore, [
                "setIsPlaying",
                "setIsLooping",
                "setCurrentMusic",
                "setCurrentMusicCurrentTime",
                "setCurrentMusicDuration"
            ]),
            play: function () {
                (this.$refs.musicAudio as HTMLAudioElement).play();
                this.setIsPlaying(true);
            },
            pause: function () {
                (this.$refs.musicAudio as HTMLAudioElement).pause();
                this.setIsPlaying(false);
            },
            changeVolume: function () {
                (this.$refs.musicAudio as HTMLAudioElement).volume = this.getUserVolume;
            },
            changeCurrentTime: function () {
                (this.$refs.musicAudio as HTMLAudioElement).currentTime = this.getCurrentMusic.currentTime;
                this.musicPlayerService.setCurrentTimePlay(this.getMusicPlayerId, this.getCurrentMusic.currentTime);
            },
            changeMusic: function (index: number) {
                this.setCurrentMusic(this.musics[index]);
                (this.$refs.musicAudio as HTMLAudioElement).addEventListener('canplay', () => {
                    (this.$refs.musicAudio as HTMLAudioElement).play();
                }, { once: true});
                (this.$refs.musicAudio as HTMLAudioElement).load();
            },
            updateCurrentTime: function () {
                this.setCurrentMusicCurrentTime((this.$refs.musicAudio as HTMLAudioElement).currentTime);
            },
            updateDuration: function () {
                this.setCurrentMusicDuration((this.$refs.musicAudio as HTMLAudioElement).duration);
            },
            loop: function () {
                this.setIsLooping(!this.getIsLooping);
            }
        },
        mounted() {
            this.musicService.findAllMusics().then(res => {
                this.musics = res
            });

            this.emitter.on('hasChangedUserVolume', () => {
                this.changeVolume();
            });

            if(this.getIsPlaying) {
                (this.$refs.musicAudio as HTMLAudioElement).addEventListener('canplay', () => {
                    this.changeVolume();
                    this.play();
                }, { once: true});
            }

            const updateUrl = new URL(process.env.MERCURE_PUBLIC_URL!);
            updateUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/musicplayer');

            /*updateEs = new EventSource(updateUrl);
            updateEs.onmessage = e => {
                let data = JSON.parse(e.data);
                let mustReload = this.isPlaying != data.isPlaying || this.currentMusic.link != data.currentMusic.link;
                this.setIsPlaying(data.isPlaying);
                this.setIsLooping(data.isLooping);
                this.setCurrentMusic(data.currentMusic);
                (this.$refs.musicAudio as HTMLAudioElement).currentTime = data.currentTimePlay;
                if (this.isPlaying && mustReload) {
                    (this.$refs.musicAudio as HTMLAudioElement).load();
                    (this.$refs.musicAudio as HTMLAudioElement).addEventListener('canplay', () => {
                        (this.$refs.musicAudio as HTMLAudioElement).play();
                    }, { once: true});
                }else if(!this.isPlaying) {
                    (this.$refs.musicAudio as HTMLAudioElement).pause();
                }
            }*/
        }
    })
</script>

<style scoped>

h2 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.music-informations {
    margin-top: 16px;
}

.music-informations.isDisable {
    opacity: 0.5;
}

.music-name {
    display: inline-block;
    width: 100%;
    text-align: center;
}

.music-timeline {
    display: grid;
    grid-template-columns: 2fr 12fr 2fr;
    column-gap: 8px;
    text-align: center;
}

.music-controls {
    position: relative;
    display: flex;
    justify-content: center;
}

.music-controls-loop {
    position: absolute;
    top: 0;
    right: 24px;
    margin-left: 24px;
    transform: rotate(0);
    transition: all .4s ease-in-out;
}

.music-controls-loop.active {
    fill: #D87D40;
    transform: rotate(360deg);
}

.music-list {
    width: 100%;
}

.music-list.isDisable {
    opacity: 0.5;
}

.music-list thead {
    border-bottom: solid 1px #565656;
}

.music-list :where(th, td) {
    text-align: left;
    padding: 8px;
}

.music-list:not(.isDisable) tbody tr:hover {
    background-color: #EAEAEA;
}

.dark .music-list:not(.isDisable) tbody tr:hover {
    background-color: #1F262D;
}

</style>