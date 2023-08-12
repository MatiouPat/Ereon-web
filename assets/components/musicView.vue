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
            </div>
        </div>
        <audio @volumechange="changeVolume" @timeupdate="updateCurrentTime" @loadeddata="updateDuration" ref="musicAudio" :src="'uploads/musics/' + currentMusic.link" loop></audio>
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
                isPlaying: false,
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
            ])
        },
        methods: {
            play: function () {
                this.$refs.musicAudio.play()
                this.isPlaying = true
            },
            pause: function (e) {
                this.$refs.musicAudio.pause()
                this.isPlaying = false
            },
            changeVolume: function () {
                this.$refs.musicAudio.volume = this.globalVolume
            },
            changeCurrentTime: function () {
                this.$refs.musicAudio.currentTime = this.currentMusic.currentTime
            },
            changeMusic: function (index) {
                this.currentMusic.link = this.musics[index].link
                this.currentMusic.title = this.musics[index].title
                this.$refs.musicAudio.load()
                this.$refs.musicAudio.addEventListener('canplay', () => {
                    this.play()
                }, { once: true})
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
            }
        },
        mounted() {
            this.emitter.on('playMusic', () => {
                this.play();
            })
            axios.get('/api/music')
                .then(response => {
                    this.musics = response.data['hydra:member']
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
    display: flex;
    justify-content: center;
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