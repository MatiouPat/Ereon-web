<template>
    <div class="music-box">
        <div class="music-informations" v-if="isGameMaster">
            <h2>Musique</h2>
            <span class="music-name">{{ currentMusic.title }}</span>
            <div class="music-timeline">
                <span>{{ currentMusic.displayedCurrentTime }}</span>
                <input type="range" min="0" v-model="currentMusic.currentTime" :max="currentMusic.duration" @input="changeCurrentTime">
                <span>{{ currentMusic.displayedDuration }}</span>
            </div>
            <div class="music-controls">
                <img :src="getIsDarkTheme ? '/build/images/icons/skip_previous_white.svg' : '/build/images/icons/skip_previous_black.svg'" alt="Previous">
                <img v-if="!isPlaying" :src="getIsDarkTheme ? '/build/images/icons/play_white.svg' : '/build/images/icons/play_black.svg'" alt="Play" @click="play">
                <img v-if="isPlaying" :src="getIsDarkTheme ? '/build/images/icons/pause_white.svg' : '/build/images/icons/pause_black.svg'" alt="Pause" @click="pause">
                <img :src="getIsDarkTheme ? '/build/images/icons/skip_next_white.svg' : '/build/images/icons/skip_next_black.svg'" alt="Next">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#565656" class="music-controls-loop" :class="{ active: isLooping }"  @click="loop"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
            </div>
        </div>
        <div class="music-informations isDisable" v-else>
            <h2>Musique</h2>
            <span class="music-name">{{ currentMusic.title }}</span>
            <div class="music-timeline">
                <span>{{ currentMusic.displayedCurrentTime }}</span>
                <input type="range" min="0" v-model="currentMusic.currentTime" disabled :max="currentMusic.duration">
                <span>{{ currentMusic.displayedDuration }}</span>
            </div>
            <div class="music-controls">
                <img src="build/images/icons/skip_previous.svg" alt="Previous">
                <img v-if="!isPlaying" src="build/images/icons/play.svg" alt="Play">
                <img v-if="isPlaying" src="build/images/icons/pause.svg" alt="Pause">
                <img src="build/images/icons/skip_next.svg" alt="Next">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#565656" class="music-controls-loop" :class="{ active: isLooping }"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
            </div>
        </div>
        <audio v-if="currentMusic.link" @volumechange="changeVolume" @timeupdate="updateCurrentTime" @loadeddata="updateDuration" ref="musicAudio" :src="'uploads/musics/' + currentMusic.link" :loop="isLooping"></audio>
        <audio v-else @volumechange="changeVolume" @timeupdate="updateCurrentTime" @loadeddata="updateDuration" ref="musicAudio" :loop="isLooping"></audio>
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
import { MusicRepository } from '../repository/musicRepository';
import { MusicPlayerRepository } from '../repository/musicplayerRepository';
import { mapState } from 'pinia';
import { useUserStore } from '../store/user';
import { useMusicStore } from '../store/music';
  
    export default defineComponent({
        data() {
            return {
                emitter: inject('emitter') as any,
                musicRepository: new MusicRepository as MusicRepository,
                musicPlayerRepository: new MusicPlayerRepository as MusicPlayerRepository,
                musicPlayerId : 0 as number,
                isPlaying: false as boolean,
                isLooping: false as boolean,
                currentMusic: {
                    title: "" as string | undefined,
                    link: "" as string | undefined,
                    duration: 0 as number,
                    displayedDuration: "" as string,
                    currentTime: 0 as number,
                    displayedCurrentTime: "" as string
                },
                musics: [] as Music[]
            }
        },
        computed: {
            ...mapState(useUserStore, [
                'isGameMaster',
                'getWorld',
                'getUserId',
                'getIsDarkTheme'
            ]),
            ...mapState(useMusicStore, [
                'getUserVolume'
            ])
        },
        methods: {
            play: function () {
                (this.$refs.musicAudio as HTMLAudioElement).play();
                this.isPlaying = true;
                this.musicPlayerRepository.setIsPlaying(this.musicPlayerId, true, this.currentMusic.currentTime);
            },
            pause: function () {
                (this.$refs.musicAudio as HTMLAudioElement).pause();
                this.isPlaying = false;
                this.musicPlayerRepository.setIsPlaying(this.musicPlayerId, false, this.currentMusic.currentTime);
            },
            changeVolume: function () {
                (this.$refs.musicAudio as HTMLAudioElement).volume = this.getUserVolume;
            },
            changeCurrentTime: function () {
                (this.$refs.musicAudio as HTMLAudioElement).currentTime = this.currentMusic.currentTime;
                this.musicPlayerRepository.setCurrentTimePlay(this.musicPlayerId, this.currentMusic.currentTime);
            },
            changeMusic: function (index: number) {
                this.currentMusic.link = this.musics[index].link;
                this.currentMusic.title = this.musics[index].title;
                this.currentMusic.currentTime = 0;
                (this.$refs.musicAudio as HTMLAudioElement).load();
                (this.$refs.musicAudio as HTMLAudioElement).addEventListener('canplay', () => {
                    this.play();
                }, { once: true});
                this.musicPlayerRepository.setCurrentMusic(this.musicPlayerId, this.musics[index].id, this.currentMusic.currentTime);
            },
            updateCurrentTime: function () {
                this.currentMusic.currentTime =(this.$refs.musicAudio as HTMLAudioElement).currentTime;
                let minutes = Math.floor(this.currentMusic.currentTime / 60);
                let secondes = Math.floor(this.currentMusic.currentTime % 60);
                this.currentMusic.displayedCurrentTime = minutes + ":" + String(secondes).padStart(2, '0');
            },
            updateDuration: function () {
                this.currentMusic.duration = (this.$refs.musicAudio as HTMLAudioElement).duration;
                let minutes = Math.floor(this.currentMusic.duration / 60);
                let secondes = Math.floor(this.currentMusic.duration % 60);
                this.currentMusic.displayedDuration = minutes + ":" + String(secondes).padStart(2, '0');
            },
            loop: function () {
                this.isLooping = !this.isLooping;
                this.musicPlayerRepository.setIsLooping(this.musicPlayerId, this.isLooping);
            }
        },
        mounted() {
            this.musicRepository.findAllMusics().then(res => {
                this.musics = res
            });

            this.emitter.on('hasChangedUserVolume', () => {
                this.changeVolume()
            })
            
            this.musicPlayerRepository.findMusicPlayerByWorld(this.getWorld.id).then(res => {
                this.musicPlayerId = res.id;
                this.isPlaying = res.isPlaying!;
                this.isLooping = res.isLooping!;
                this.currentMusic.title = res.currentMusic!.title;
                this.currentMusic.link = res.currentMusic!.link;
                if (this.isPlaying) {
                    (this.$refs.musicAudio as HTMLAudioElement).load();
                    (this.$refs.musicAudio as HTMLAudioElement).currentTime = res.currentTimePlay!;
                    (this.$refs.musicAudio as HTMLAudioElement).addEventListener('canplay', () => {
                        (this.$refs.musicAudio as HTMLAudioElement).play();
                    }, { once: true});
                }
                this.changeVolume();

                const updateUrl = new URL(process.env.MERCURE_PUBLIC_URL!);
                updateUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/musicplayer');

                const updateEs = new EventSource(updateUrl);
                updateEs.onmessage = e => {
                    let data = JSON.parse(e.data);
                    let mustReload = this.isPlaying != data.isPlaying || this.currentMusic.link != data.currentMusic.link;
                    this.isPlaying = data.isPlaying;
                    this.isLooping = data.isLooping;
                    this.currentMusic.title = data.currentMusic.title;
                    this.currentMusic.link = data.currentMusic.link;
                    (this.$refs.musicAudio as HTMLAudioElement).currentTime = data.currentTimePlay;
                    if (this.isPlaying && mustReload) {
                        (this.$refs.musicAudio as HTMLAudioElement).load();
                        (this.$refs.musicAudio as HTMLAudioElement).addEventListener('canplay', () => {
                            (this.$refs.musicAudio as HTMLAudioElement).play();
                        }, { once: true});
                    }else if(!this.isPlaying) {
                        (this.$refs.musicAudio as HTMLAudioElement).pause();
                    }
                }
            })
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
    display: flex;
    justify-content: stretch;
    align-items: center;
    gap: 8px;
}

.music-timeline input {
    width: 100%;
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

</style>