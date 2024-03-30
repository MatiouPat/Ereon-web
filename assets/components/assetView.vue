<template>
    <div class="assets-box">
        <div class="assets">
            <img v-for="asset in assets" :key="asset.id" v-on="{ dragend: getLayer !== 3 ? addToken : null }" :src="'/uploads/images/' + asset.image.imageName" :alt="asset.id.toString()" width="64" height="64">
        </div>
        <button class="btn btn-primary" @click="openAssetPage">Ajouter un asset</button>
        <modal
            :isDisplayed="isAdditionDisplayed"
            :modalTitleMessage="'Ajouter un asset'"
            :modalValidationMessage="'Ajouter l\'asset'"
            @modal:close="isAdditionDisplayed = false"
            @modal:validation="addAsset"
        >
            <template v-slot:modal-body>
                <image-input v-if="isAdditionDisplayed" :modelImage="newAsset.image" :width="192" :height="192" @update:modelImage="(modelImage) => newAsset.image = modelImage"></image-input>
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
                newAsset: {} as Asset,
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
                this.isAdditionDisplayed = true;
            },
            addAsset: function() {
                this.assetService.createAsset(this.newAsset).then((asset: Asset) => {
                    this.assets.push(asset);
                })
                this.newAsset = {};
                this.isAdditionDisplayed = false;
            }
        },
        mounted: function() {
            this.assetService.findAllAssets().then((res: Asset[]) => {
                this.assets = res;
            })
        }
    })
</script>

<style scoped>
    .assets-box {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .assets {
        display: flex;
        align-content: flex-start;
        flex-wrap: wrap;
        flex-grow: 1;
        gap: 8px;
        margin-bottom: 8px;
    }

    .btn {
        width: 100%;
    }
</style>