<template>
    <div class="assets-box">
        <picture v-for="asset in assets" @dragend="addToken">
            <source type="image/webp" :srcset="'/uploads/images/asset/' + asset.compressedImage">
            <img :src="'/uploads/images/asset/' + asset.image" :alt="asset.id" width="64" height="64">
        </picture>
    </div>
</template>

<script>
import axios from 'axios';
import { mapActions } from 'vuex';

    export default {
        data() {
            return {
                /**
                 * The list of all assets
                 */
                assets: []
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
                if(document.elementsFromPoint(e.pageX, e.pageY).includes(document.getElementById('editor-zone'))) {
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
    }
</script>

<style scoped>
    .assets-box {
        display: flex;
        gap: 8px;
    }
</style>