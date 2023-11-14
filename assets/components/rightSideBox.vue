<template>
    <ul class="right-side-box-actions">
        <li title="Chat" @click="choose(0)">
            <svg height="24px" viewBox="0 0 24 24" width="24px" :fill="pageIndex == 0 ? '#D68836' : getIsDarkTheme ? '#F3F4F4' : '#1F262D'"><path d="M0 0h24v24H0z" fill="none"/><path d="M21 3H3v18h18V3zM7.5 18c-.83 0-1.5-.67-1.5-1.5S6.67 15 7.5 15s1.5.67 1.5 1.5S8.33 18 7.5 18zm0-9C6.67 9 6 8.33 6 7.5S6.67 6 7.5 6 9 6.67 9 7.5 8.33 9 7.5 9zm4.5 4.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5 4.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm0-9c-.83 0-1.5-.67-1.5-1.5S15.67 6 16.5 6s1.5.67 1.5 1.5S17.33 9 16.5 9z"/></svg>
        </li>
        <li title="Personages" @click="choose(1)">
            <svg height="24px" viewBox="0 0 24 24" width="24px" :fill="pageIndex == 1 ? '#D68836' : getIsDarkTheme ? '#F3F4F4' : '#1F262D'"><g><rect fill="none" height="24" width="24"/></g><g><path d="M21,3H3v18h18V3z M12,6c1.93,0,3.5,1.57,3.5,3.5c0,1.93-1.57,3.5-3.5,3.5s-3.5-1.57-3.5-3.5C8.5,7.57,10.07,6,12,6z M19,19 H5v-0.23c0-0.62,0.28-1.2,0.76-1.58C7.47,15.82,9.64,15,12,15s4.53,0.82,6.24,2.19c0.48,0.38,0.76,0.97,0.76,1.58V19z"/></g></svg>
        </li>
        <li title="Jukebox" v-if="isGameMaster" @click="choose(2)">
            <svg height="24px" viewBox="0 0 24 24" width="24px" :fill="pageIndex == 2 ? '#D68836' : getIsDarkTheme ? '#F3F4F4' : '#1F262D'"><path d="M0 0h22v22H0V0z" style="fill:none"/><path d="M20.17 1.83H1.83v18.33h18.33V1.83zm-4.58 5.73h-3.44v6.3A2.86 2.86 0 1 1 9.29 11c.65 0 1.24.22 1.72.58V5.27h4.58v2.29z"/></svg>
        </li>
        <li title="Assets" v-if="isGameMaster" @click="choose(3)">
            <svg height="24px" viewBox="0 0 24 24" width="24px" :fill="pageIndex == 3 ? '#D68836' : getIsDarkTheme ? '#F3F4F4' : '#1F262D'"><path d="m0,0h24v24H0V0Z" style="fill:none;"/><path d="m21,21V3H3v18h18Zm-12.38-6.75l2.28,3.05,3.34-4.17,4.5,5.62H5.25l3.38-4.5Z"/></svg>
        </li>
    </ul>
    <div class="right-side-box-content" v-if="isDownloaded">
        <dialog-view v-show="pageIndex == 0"></dialog-view>
        <personage-view v-show="pageIndex == 1"></personage-view>
        <music-view v-show="pageIndex == 2"></music-view>
        <asset-view v-if="isGameMaster" v-show="pageIndex == 3"></asset-view>
    </div>
</template>

<script lang="ts">
import DialogView from './dialogView.vue'
import AssetView from './assetView.vue'
import MusicView from './musicView.vue'
import PersonageView from './personages/personageView.vue'
import { mapGetters } from 'vuex'
import { defineComponent, inject } from 'vue'

    export default defineComponent({
        components: {
            DialogView,
            PersonageView,
            MusicView,
            AssetView
        },
        data () {
            return {
                emitter: inject('emitter') as any,
                /**
                 * The index to choose the component to display
                 */
                pageIndex: 0 as number,
                isDownloaded: false as boolean
            }
        },
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
                'getIsDarkTheme'
            ])
        },
        methods: {
            /**
             * Change the component to display
             * @param {*} index 
             */
            choose: function(index: number) {
                this.pageIndex = index
            }
        },
        mounted() {
            this.emitter.on('isDownload', () => {
                this.isDownloaded = true
            })
        },
    })
</script>

<style scoped>
    .right-side-box-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 8px 0;
        border-bottom: solid 1px #565656;
        background-color: #D7D9DB;
    }

    .right-side-box-actions svg {
        transition: all 0.2s ease-in-out;
    }

    .right-side-box-content {
        padding: 16px 8px;
        height: calc(100dvh - 42px);
        width: 300px;
        background-color: #FFFFFF;
    }

    .dark .right-side-box-content {
        background-color: #364049;
    }

    .dark .right-side-box-actions {
        background-color: #1F262D;
    }
</style>