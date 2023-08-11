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
            <span class="music-name">{{ currentMusic.name }}</span>
            <div class="music-timeline">
                <span>{{ currentMusic.displayedCurrentTime }}</span>
                <input type="range" min="0" v-model="currentMusic.currentTime" :max="currentMusic.duration" @input="changeCurrentTime">
                <span>{{ currentMusic.displayedDuration }}</span>
            </div>
            <div class="music-controls">
                <img src="build/images/icons/skip_previous.svg" alt="Previous">
                <img v-if="!isPlaying" src="build/images/icons/play.svg" alt="Play" @click="playMusic">
                <img v-if="isPlaying" src="build/images/icons/pause.svg" alt="Pause" @click="pauseMusic">
                <img src="build/images/icons/skip_next.svg" alt="Next">
            </div>
            <audio v-if="isPlaying" @play="updateDuration" @volumechange="changeVolume" @timeupdate="updateCurrentTime" ref="musicAudio" src="uploads/musics/01 Bastion.mp3" autoplay loop></audio>
        </div>
        <div class="music-informations isDisable" v-else>
            <h2>Musique</h2>
            <span class="music-name">{{ currentMusic.name }}</span>
            <div class="music-timeline">
                <span>{{ currentMusic.displayedCurrentTime }}</span>
                <input type="range" min="0" v-model="currentMusic.currentTime" :max="currentMusic.duration">
                <span>{{ currentMusic.displayedDuration }}</span>
            </div>
            <div class="music-controls">
                <img src="build/images/icons/skip_previous.svg" alt="Previous">
                <img v-if="!isPlaying" src="build/images/icons/play.svg" alt="Play">
                <img v-if="isPlaying" src="build/images/icons/pause.svg" alt="Pause">
                <img src="build/images/icons/skip_next.svg" alt="Next">
            </div>
            <audio v-if="isPlaying" @play="updateDuration" @timeupdate="updateCurrentTime" ref="musicAudio" src="uploads/musics/01 Bastion.mp3" autoplay loop></audio>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

  
    export default {
        data() {
            return {
                globalVolume: 1,
                currentMusic: {
                    name: "01 Bastion",
                    duration: 0,
                    displayedDuration: "",
                    currentTime: 0,
                    displayedCurrentTime: ""
                },
            }
        },
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
            ]),
            ...mapGetters('music', [
                'isPlaying'
            ])
        },
        methods: {
            ...mapActions('music', [
                'playMusic',
                'pauseMusic'
            ]),
            changeVolume: function () {
                this.$refs.musicAudio.volume = this.globalVolume
            },
            changeCurrentTime: function () {
                this.$refs.musicAudio.currentTime = this.currentMusic.currentTime
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

</style>