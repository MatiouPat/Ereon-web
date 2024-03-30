<template>
    <div class="assets-box">
        <button class="btn btn-primary" @click="openAssetPage">Ajouter un asset</button>
        <div class="assets">
            <img v-for="asset in assets" :key="asset.id" v-on="{ dragend: getLayer !== 3 ? addToken : null }" :src="'/uploads/images/' + asset.image.imageName" :alt="asset.id.toString()" width="64" height="64">
        </div>
        <modal
            :isDisplayed="isAdditionDisplayed"
            :modalTitleMessage="'Ajouter un asset'"
            :modalValidationMessage="'Ajouter l\'asset'"
            @modal:close="isAdditionDisplayed = false"
            @modal:validation="addAsset"
        >
            <template v-slot:modal-body>
                <image-input :modelImage="asset.image" :width="192" :height="192" @update:modelImage="(modelImage) => asset.image = modelImage"></image-input>
            </template>
        </modal>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapActions, mapGetters } from 'vuex';
import { Asset } from '../entity/asset';
import { AssetService } from '../services/assetService';
import ImageInput from './form/imageInput.vue';
import Modal from './modal/modal.vue';

    export default defineComponent({
        data() {
            return {
                assetService: new AssetService as AssetService,
                /**
                 * The list of all assets
                 */
                assets: [] as Asset[],
                asset: {} as Asset,
                isAdditionDisplayed: false as boolean
            }
        },
        computed: {
            ...mapGetters('map', [
                'getLayer'
            ])
        },
        components: { Modal, ImageInput },
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
            },
            openAssetPage: function() {
                this.asset = {};
                this.isAdditionDisplayed = true;
            },
            addAsset: function() {
                this.assetService.createAsset(this.asset).then((asset: Asset) => {
                    this.assets.push(asset);
                })
                this.isAdditionDisplayed = false;
            }
        },
        mounted: function() {
            this.assetService.findAllAssets().then(res => {
                console.log(res)
                this.assets = res;
            })
        }
    })
</script>

<style scoped>
    .assets {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
</style>