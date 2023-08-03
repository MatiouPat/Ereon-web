<template>
    <ul class="right-side-box-actions">
        <li @click="choose(0)"><img src="/build/images/dice.svg" alt="Dice"></li>
        <li v-if="isGameMaster" @click="choose(1)"><img src="/build/images/asset.svg" alt="Assets"></li>
    </ul>
    <div class="right-side-box-content">
        <keep-alive>
            <component :is="pages[pageIndex]"></component>
        </keep-alive>
    </div>
</template>

<script>
import DialogView from './dialogView.vue'
import AssetView from './assetView.vue'
import { mapGetters } from 'vuex'

    export default {
        components: {
            DialogView,
            AssetView
        },
        data () {
            return {
                pages: [
                    DialogView,
                    AssetView
                ],
                pageIndex: 0
            }
        },
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
            ]),
        },
        methods: {
            choose: function(index) {
                this.pageIndex = index
            }
        }
    }
</script>

<style scoped>
    .right-side-box-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding-top: 8px;
    }
    .right-side-box-content {
        padding: 16px 8px;
    }
</style>