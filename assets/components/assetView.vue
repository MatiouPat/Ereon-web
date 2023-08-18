<template>
    <div class="assets-box">
        <picture v-for="asset in assets" @dragend="addToken" :key="asset.id">
            <source type="image/webp" :srcset="'/uploads/images/asset/' + asset.compressedImage">
            <img :src="'/uploads/images/asset/' + asset.image" alt="" width="64" height="64">
        </picture>
    </div>
</template>

<script lang="ts">
import axios from 'axios';
import { defineComponent } from 'vue';
import { mapActions } from 'vuex';
import { Asset } from '../interfaces/asset';

    export default defineComponent({
        data() {
            return {
                /**
                 * The list of all assets
                 */
                assets: [] as Asset[]
            }
        },
        methods: {
            ...mapActions('map', [
                'addTokenOnMap'
            ]),
            /**
             * Add a token with the selected asset to the current map 
             * @param {*} e 
             */
            addToken: function(e) {
                if(document.elementsFromPoint(e.pageX, e.pageY).includes(document.getElementById('editor'))) {
                    this.addTokenOnMap({
                        id: e.target.alt,
                        mercure: false
                    })
                }
            }
        },
        mounted: function() {
            axios.get('/api/assets')
                .then(response => {
                    this.assets = response.data['hydra:member']
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