<template>
    <div class="music-box">
        <div>
            <h2>Paramètres</h2>
            <div>
                <span>Volume global</span>
                <div>
                    <img src="build/images/icons/volume.svg" alt="Volume">
                    <input type="range" min="0" v-model="globalVolume" max="1" step="0.01" @input="changeVolume">
                </div>
            </div>
        </div>
        <div class="music-informations" v-if="isGameMaster">
            <h2>Musique</h2>
            <span class="music-name">{{ currentMusic.title }}</span>
            <div class="music-timeline">
                <span>{{ currentMusic.displayedCurrentTime }}</span>
                <input type="range" min="0" v-model="currentMusic.currentTime" :max="currentMusic.duration" @input="changeCurrentTime">
                <span>{{ currentMusic.displayedDuration }}</span>
            </div>
            <div class="music-controls">
                <img src="build/images/icons/skip_previous.svg" alt="Previous">
                <img v-if="!isPlaying" src="build/images/icons/play.svg" alt="Play" @click="play">
                <img v-if="isPlaying" src="build/images/icons/pause.svg" alt="Pause" @click="pause">
                <img src="build/images/icons/skip_next.svg" alt="Next">
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
        <audio @volumechange="changeVolume" @timeupdate="updateCurrentTime" @loadeddata="updateDuration" ref="musicAudio" :src="'uploads/musics/' + currentMusic.link" :loop="isLooping"></audio>
        <table class="music-list" :class="{isDisable: !isGameMaster}">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                </tr>
            </thead>
            <tbody v-if="isGameMaster">
                <tr @click="changeMusic(index)" v-for="(music, index) in musics">
                    <td>{{ index+1 }}</td>
                    <td>{{ music.title }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(music, index) in musics">
                    <td>{{ index+1 }}</td>
                    <td>{{ music.title }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';

  
    export default {
        data() {
            return {
                musicPlayerId : 0,
                isPlaying: false,
                isLooping: false,
                globalVolume: 1,
                currentMusic: {
                    title: "Bastion",
                    link: "01 Bastion.mp3",
                    duration: 0,
                    displayedDuration: "",
                    currentTime: 0,
                    displayedCurrentTime: ""
                },
                musics: []
            }
        },
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
                'getWorld'
            ])
        },
        methods: {
            play: function () {
                this.$refs.musicAudio.play()
                this.isPlaying = true
                console.log(this.currentMusic.currentTime)
                axios.patch('/api/music_players/' + this.musicPlayerId, {
                    isPlaying: true,
                    currentTimePlay: this.currentMusic.currentTime
                }, {
                    headers: {
                        'Content-Type': 'application/merge-patch+json'
                    }
                })
            },
            pause: function (e) {
                this.$refs.musicAudio.pause()
                this.isPlaying = false
                axios.patch('/api/music_players/' + this.musicPlayerId, {
                    isPlaying: false,
                    currentTimePlay: this.currentMusic.currentTime
                }, {
                    headers: {
                        'Content-Type': 'application/merge-patch+json'
                    }
                })
            },
            changeVolume: function () {
                this.$refs.musicAudio.volume = this.globalVolume
            },
            changeCurrentTime: function () {
                this.$refs.musicAudio.currentTime = this.currentMusic.currentTime
                axios.patch('/api/music_players/' + this.musicPlayerId, {
                    currentTimePlay: Number(this.currentMusic.currentTime)
                }, {
                    headers: {
                        'Content-Type': 'application/merge-patch+json'
                    }
                })
            },
            changeMusic: function (index) {
                this.currentMusic.link = this.musics[index].link
                this.currentMusic.title = this.musics[index].title
                this.$refs.musicAudio.load()
                this.$refs.musicAudio.addEventListener('canplay', () => {
                    this.play()
                }, { once: true})
                axios.patch('/api/music_players/' + this.musicPlayerId, {
                    currentMusic: 'api/music/' + this.musics[index].id,
                    currentTimePlay: 0
                }, {
                    headers: {
                        'Content-Type': 'application/merge-patch+json'
                    }
                })
            },
            updateCurrentTime: function () {
                this.currentMusic.currentTime = this.$refs.musicAudio.currentTime;
                let minutes = Math.floor(this.currentMusic.currentTime / 60);
                let secondes = Math.floor(this.currentMusic.currentTime % 60);
                this.currentMusic.displayedCurrentTime = minutes + ":" + String(secondes).padStart(2, '0');
            },
            updateDuration: function () {
                this.currentMusic.duration = this.$refs.musicAudio.duration;
                let minutes = Math.floor(this.currentMusic.duration / 60);
                let secondes = Math.floor(this.currentMusic.duration % 60);
                this.currentMusic.displayedDuration = minutes + ":" + String(secondes).padStart(2, '0');
            },
            loop: function () {
                this.isLooping = !this.isLooping
                axios.patch('/api/music_players/' + this.musicPlayerId, {
                    isLooping: this.isLooping,
                    currentTimePlay: this.currentMusic.currentTime
                }, {
                    headers: {
                        'Content-Type': 'application/merge-patch+json'
                    }
                })
            }
        },
        mounted() {
            axios.get('/api/music')
                .then(response => {
                    this.musics = response.data['hydra:member']
                })

            this.emitter.on('playMusic', () => {
                axios.get('/api/music_players?world.id=' + this.getWorld.id)
                    .then(response => {
                        let musicPlayer = response.data['hydra:member'][0]
                        this.musicPlayerId = musicPlayer.id
                        console.log(this.musicPlayerId)
                        this.isPlaying = musicPlayer.isPlaying
                        this.isLooping = musicPlayer.isLooping
                        this.currentMusic.title = musicPlayer.currentMusic.title
                        this.currentMusic.link = musicPlayer.currentMusic.link
                        if (this.isPlaying) {
                            this.$refs.musicAudio.load()
                            this.$refs.musicAudio.currentTime = musicPlayer.currentTimePlay
                            this.$refs.musicAudio.addEventListener('canplay', () => {
                                this.$refs.musicAudio.play()
                            }, { once: true})
                        }

                        const updateUrl = new URL(process.env.MERCURE_PUBLIC_URL);
                        updateUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/musicplayer');

                        const updateEs = new EventSource(updateUrl);
                        updateEs.onmessage = e => {
                            let data = JSON.parse(e.data)
                            let mustReload = this.isPlaying != data.isPlaying || this.currentMusic.link != data.currentMusic.link
                            this.isPlaying = data.isPlaying
                            this.isLooping = data.isLooping
                            this.currentMusic.title = data.currentMusic.title
                            this.currentMusic.link = data.currentMusic.link
                            this.$refs.musicAudio.currentTime = data.currentTimePlay
                            if (this.isPlaying && mustReload) {
                                this.$refs.musicAudio.load()
                                this.$refs.musicAudio.addEventListener('canplay', () => {
                                    this.$refs.musicAudio.play()
                                }, { once: true})
                            }else if(!this.isPlaying) {
                                this.$refs.musicAudio.pause()
                            }
                        }
                    })
            })
        }
    }
</script>

<style scoped>

h2 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

input[type = range] {
    accent-color: #D68836;
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
    fill: #D68836;
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