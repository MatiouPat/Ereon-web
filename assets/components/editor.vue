<template>
    <div class="editor-wrapper" @mousedown="onMouseDown" @mouseup="onMouseUp" @wheel="onWheel" @mouseleave="onMouseUp" @contextmenu="onContextMenu" ref="editor">
        <div class="editor" id="editor-zone" ref="map" :style="{ width: map.map.width + 'px', height: map.map.height + 'px', transform: 'scale(' + ratio + ')', margin: margin * ratio * 2 + 'px' }">
            <Token :id="token.id" v-for="token in map.tokens"></Token>
        </div>
    </div>
</template>

<script>
import Token from './token.vue'
import { mapActions, mapState } from 'vuex';

    export default {
        components: {
            Token
        },
        data() {
            return {
                ratio: 1,
                margin: 200,
                startX: 0,
                startY: 0,
                mapX: 0,
                mapY: 0 
            }
        },
        props: [
            'user'
        ],
        computed: mapState({
            map: state => state.map
        }),
        methods: {
            ...mapActions('map', [
                'addTokenOnMap',
                'setMap',
                'updateToken',
                'removeTokenOnMap'
            ]),
            /**
             * 
             * @param {*} e 
             */
            onMouseDown: function (e) {
                e.preventDefault();
                if(e.button === 2) {
                    this.startX = e.screenX - this.$el.offsetLeft;
                    this.startY = e.screenY - this.$el.offsetTop;
                    this.mapX = this.$el.scrollLeft;
                    this.mapY = this.$el.scrollTop;
                    document.addEventListener('mousemove', this.onMouseMove);
                }
            },
            /**
             * 
             * @param {*} e 
             */
            onMouseMove: function (e) {
                this.$el.scrollLeft = this.mapX - (e.screenX - this.$el.offsetLeft - this.startX)
                this.$el.scrollTop = this.mapY - (e.screenY - this.$el.offsetTop - this.startY)
            },
            /**
             * 
             */
            onMouseUp: function () {
                document.removeEventListener('mousemove', this.onMouseMove);
            },
            /**
             * 
             * @param {*} e 
             */
            onWheel: function (e) {
                e.preventDefault();
                if(e.ctrlKey == true){
                    if (this.ratio - e.deltaY * 0.0005 <= 0.1) {
                        this.ratio = 0.1;
                    } else if (this.ratio - e.deltaY * 0.00045 >= 2.3) {
                        this.ratio = 2.3;
                    } else if (this.ratio >= 0.1 && this.ratio <= 2.3) {
                        this.ratio = this.ratio - e.deltaY * 0.0005;
                    }
                }
            },
            /**
             * 
             * @param {*} e 
             */
            onContextMenu: function (e) {
                e.preventDefault();
            }
        },
        mounted() {
            this.setMap(this.user.map.id)

            const postUrl = new URL(process.env.MERCURE_PUBLIC_URL);
            postUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/token/post');

            const postEs = new EventSource(postUrl);
            postEs.onmessage = e => {
                let data = JSON.parse(e.data)
                console.log("post")
                this.addTokenOnMap({
                    id: data.id,
                    width: data.width,
                    height: data.height,
                    top: data.topPosition,
                    left: data.leftPosition,
                    zIndex: data.zIndex,
                    image: data.asset.image,
                    compressedImage: data.asset.compressedImage,
                    mercure: true
                })
            }

            const updateUrl = new URL(process.env.MERCURE_PUBLIC_URL);
            updateUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/token/update');

            const updateEs = new EventSource(updateUrl);
            updateEs.onmessage = e => {
                let data = JSON.parse(e.data)
                console.log("update")
                this.updateToken({
                    id: data.id,
                    width: data.width,
                    height: data.height,
                    top: data.topPosition,
                    left: data.leftPosition,
                    zIndex: data.zIndex
                })
            }

            const deleteUrl = new URL(process.env.MERCURE_PUBLIC_URL);
            deleteUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/token/remove');

            const deleteEs = new EventSource(deleteUrl);
            deleteEs.onmessage = e => {
                let data = JSON.parse(e.data)
                console.log("remove")
                this.removeTokenOnMap({
                    id: data.id,
                    mercure: true
                })
            }
        }
    }
</script>

<style scoped>
    .editor-wrapper {
        overflow: scroll;
        height: 100%;
        width: calc(100dvw - 445px);
        background-color: #E2E2E2;
    }

    .editor {
        position: relative;
        display: inline-block;
        margin: 800px;
        width: 2000px;
        background-color: #FFF;
    }

    .editor picture {
        max-width: 1200px;
    }

    .editor img {
        max-width: 1200px;
    }
</style>