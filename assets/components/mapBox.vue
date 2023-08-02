<template>
    <div class="map-box" :class="{isDisplayed: isDisplayed, isVisible: isGameMaster}">
        <div class="maps">
            <div class="map" @click="setMap(map.id)" v-for="map in maps">{{ map.name }}</div>
        </div>
        <button class="map-open" name="menu" type="button" :class="{isDisplayed: isDisplayed}" @click="display">
            <svg width="32" height="32" viewBox="0 0 100 100">
                <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
                <path class="line line2" d="M 20,50 H 80" />
                <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
            </svg>
        </button>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

    export default {
        data() {
            return {
                isDisplayed: false
            }
        },
        props: [
            'maps'
        ],
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
            ]),
        },
        methods: {
            ...mapActions('map', [
                'setMap'
            ]),
            display: function () {
                this.isDisplayed = !this.isDisplayed;
            }
        }
    }
</script>

<style scoped>

    .map-box {
        display: none;
        position: absolute;
        top: -256px;
        left: 10%;
        width: 80%;
        height: 256px;
        z-index: 5;
        background-color: #FFF;
        transition: .1s ease-in-out;
    }

    .map-box.isVisible {
        display: block;
    }

    .map-box.isDisplayed {
        top: 0;
    }

    .map-open {
        position: absolute;
        bottom: -40px;
        left: 80%;
        width: 40px;
        height: 40px;
        background-color: #D68836;
        border: none;
        cursor: pointer;
        padding: 4px;
    }

    .line {
        fill: none;
        stroke: #FFF;
        stroke-width: 6;
        transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
            stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .line1 {
        stroke-dasharray: 60 207;
        stroke-width: 6;
    }

    .line2 {
        stroke-dasharray: 60 60;
        stroke-width: 6;
    }

    .line3 {
        stroke-dasharray: 60 207;
        stroke-width: 6;
    }

    .isDisplayed .line1 {
        stroke-dasharray: 90 207;
        stroke-dashoffset: -134;
        stroke-width: 6;
    }

    .isDisplayed .line2 {
        stroke-dasharray: 1 60;
        stroke-dashoffset: -30;
        stroke-width: 6;
    }

    .isDisplayed .line3 {
        stroke-dasharray: 90 207;
        stroke-dashoffset: -134;
        stroke-width: 6;
    }

    .maps {
        display: flex;
        gap: 16px;
        width: 100%;
        height: 100%;
        overflow-y: scroll;
        padding: 8px;
    }

    .maps::-webkit-scrollbar {
        width: .4em;
    }
    
    .maps::-webkit-scrollbar-thumb {
        background-color: #666666;
    }

    .map {
        background-color: dimgrey;
        width: 128px;
        height: 128px;
    }

</style>