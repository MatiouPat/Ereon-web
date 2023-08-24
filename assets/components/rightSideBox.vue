<template>
    <ul class="right-side-box-actions">
        <li title="Chat" @click="choose(0)">
            <svg height="24px" viewBox="0 0 24 24" width="24px" :fill="pageIndex == 0 ? '#D68836' : '#565656'"><path d="M0 0h24v24H0z" fill="none"/><path d="M21 3H3v18h18V3zM7.5 18c-.83 0-1.5-.67-1.5-1.5S6.67 15 7.5 15s1.5.67 1.5 1.5S8.33 18 7.5 18zm0-9C6.67 9 6 8.33 6 7.5S6.67 6 7.5 6 9 6.67 9 7.5 8.33 9 7.5 9zm4.5 4.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5 4.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm0-9c-.83 0-1.5-.67-1.5-1.5S15.67 6 16.5 6s1.5.67 1.5 1.5S17.33 9 16.5 9z"/></svg>
        </li>
        <li title="Jukebox" v-if="isGameMaster" @click="choose(1)">
            <svg height="24px" viewBox="0 0 24 24" width="24px" :fill="pageIndex == 1 ? '#D68836' : '#565656'"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M22 2H6v16h16V2zm-4 5h-3v5.5c0 1.38-1.12 2.5-2.5 2.5S10 13.88 10 12.5s1.12-2.5 2.5-2.5c.57 0 1.08.19 1.5.51V5h4v2zM4 6H2v16h16v-2H4V6z"/></svg>
        </li>
        <li title="Assets" v-if="isGameMaster" @click="choose(2)">
            <svg height="24px" viewBox="0 0 24 24" width="24px" :fill="pageIndex == 2 ? '#D68836' : '#565656'"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M22 18V2H6v16h16zm-11-6l2.03 2.71L16 11l4 5H8l3-4zM2 6v16h16v-2H4V6H2z"/></svg>
        </li>
    </ul>
    <div class="right-side-box-content">
        <dialog-view v-show="pageIndex == 0"></dialog-view>
        <music-view v-show="pageIndex == 1"></music-view>
        <asset-view v-if="isGameMaster" v-show="pageIndex == 2"></asset-view>
    </div>
</template>

<script lang="ts">
import DialogView from './dialogView.vue'
import AssetView from './assetView.vue'
import MusicView from './musicView.vue'
import { mapGetters } from 'vuex'
import { defineComponent } from 'vue'

    export default defineComponent({
        components: {
            DialogView,
            MusicView,
            AssetView
        },
        data () {
            return {
                /**
                 * The list of components
                 */
                pages: [
                    DialogView,
                    MusicView,
                    AssetView
                ],
                /**
                 * The index to choose the component to display
                 */
                pageIndex: 0 as number
            }
        },
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
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
        }
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
    }

    .right-side-box-actions svg {
        transition: all 0.2s ease-in-out;
    }

    .right-side-box-content {
        padding: 16px 8px;
    }
</style>