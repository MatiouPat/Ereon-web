<template>
    <div class="assets-box">
        <picture v-for="asset in assets" :key="asset.id" v-on="{ dragend: getLayer !== 3 ? addToken : null }">
            <source type="image/webp" :srcset="'/uploads/images/asset/' + asset.compressedImage">
            <img :src="'/uploads/images/asset/' + asset.image" :alt="asset.id.toString()" width="64" height="64">
        </picture>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapActions, mapGetters } from 'vuex';
import { Asset } from '../entity/asset';
import { AssetRepository } from '../repository/assetRepository';

    export default defineComponent({
        data() {
            return {
                assetRepository: new AssetRepository as AssetRepository,
                /**
                 * The list of all assets
                 */
                assets: [] as Asset[]
            }
        },
        computed: {
            ...mapGetters('map', [
                'getLayer'
            ])
        },
        methods: {
            ...mapActions('map', [
                'addTokenOnMap'
            ]),
            /**
             * Add a token with the selected asset to the current map 
             * @param {*} e 
             */
            addToken: function(e: DragEvent) {
                if(document.elementsFromPoint(e.pageX, e.pageY).includes(document.getElementById('editor')!)) {
                    this.addTokenOnMap({
                        mercure: false,
                        data: Number((e.target as HTMLImageElement).alt),
                    })
                }
            }
        },
        mounted: function() {
            this.assetRepository.findAllAssets().then(res => {
                this.assets = res;
            })
        }
    })
</script>

<style scoped>
    .assets-box {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
</style>