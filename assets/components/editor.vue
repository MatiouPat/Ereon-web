<template>
    <div class="editor-wrapper" id="editor-wrapper" ref="editorWrapper" @mousedown="onMouseDown" @mouseup="onMouseUp" @wheel="onWheel" @mouseleave="onMouseUp" @contextmenu="onContextMenu">
        <div class="editor" id="editor" ref="map" :style="{ width: map.width + 'px', height: map.height + 'px', transform: 'scale(' + ratio + ')'}">
            <!--<canvas ref="main" id="main" :width="map.width" :height="map.height"></canvas>
            <canvas ref="fog" id="fog" :width="map.width" :height="map.height"></canvas>
            <canvas ref="dark" id="dark" :width="map.width" :height="map.height"></canvas>-->
            <Token :id="token.id" v-for="token in tokens"></Token>
        </div>
        <div class="editor-zoom">
            <span class="editor-zoom-ratio">{{(ratio * 100).toFixed(0)}}</span>
            <button class="editor-zoom-add-btn" @mousedown="zoomIn">+</button>
            <input class="editor-zoom-bar" type="range" min="0.1" max="2.3" v-model="ratio" step="0.01">
            <button class="editor-zoom-minus-btn" @mousedown="zoomOut">-</button>
        </div>
    </div>
</template>

<script>
import Token from './token.vue'
import { mapActions, mapGetters } from 'vuex';

    export default {
        components: {
            Token
        },
        data() {
            return {
                /**
                 * The zoom in on the map
                 */
                ratio: 1,
                /**
                 * The margin around the map
                 */
                margin: 200,
                /**
                 * The X position where the user begins scrolling
                 */
                startX: 0,
                /**
                 * The Y position where the user begins scrolling
                 */
                startY: 0,
                /**
                 * The X position at which the user begins scrolling in relation to the map
                 */
                mapX: 0,
                /**
                 * The Y position at which the user begins scrolling in relation to the map
                 */
                mapY: 0,
                /*fog: null,
                dark: null,
                main: null*/
            }
        },
        computed: {
            ...mapGetters('map', [
                'map',
                'tokens'
            ])
        },
        methods: {
            ...mapActions('map', [
                'addTokenOnMap',
                'updateToken',
                'removeTokenOnMap'
            ]),
            /**
             * Starts scrolling the map after right-clicking
             * @param {*} e 
             */
            onMouseDown: function (e) {
                if(e.button === 2) {
                    this.startX = e.screenX - this.$el.offsetLeft;
                    this.startY = e.screenY - this.$el.offsetTop;
                    this.mapX = this.$el.scrollLeft;
                    this.mapY = this.$el.scrollTop;
                    document.addEventListener('mousemove', this.onMouseMove);
                }
            },
            /**
             * Calculating scrolling position during movement
             * @param {*} e 
             */
            onMouseMove: function (e) {
                this.$el.scrollLeft = this.mapX - (e.screenX - this.$el.offsetLeft - this.startX)
                this.$el.scrollTop = this.mapY - (e.screenY - this.$el.offsetTop - this.startY)
            },
            /**
             * Stops scrolling after click release
             */
            onMouseUp: function () {
                document.removeEventListener('mousemove', this.onMouseMove);
            },
            /**
             * Calculate zoom using mouse wheel
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
             * Avoid right-click context menus
             * @param {*} e 
             */
            onContextMenu: function (e) {
                e.preventDefault();
            },
            /*draw: function (e) {
                let pos = {x: e.layerX, y: e.layerY };
                console.log(this.tokens)
                let x = pos.x;
                let y = pos.y;

                this.fog.clearRect(0, 0, this.map.width, this.map.height)
                this.dark.clearRect(0, 0, this.map.width, this.map.height)

                this.fog.globalAlpha = 1;
                this.fog.fillStyle = "black";
                this.fog.fillRect(0, 0, this.map.width, this.map.height);
                this.fog.globalCompositeOperation = 'destination-out';
                let fog_gd = this.fog.createRadialGradient(x, y, 150, x, y, 0)
                fog_gd.addColorStop(0, 'rgba(0, 0, 0, 0)');
                fog_gd.addColorStop(1, 'rgba(0, 0, 0, 0.2)');
                this.fog.fillStyle = fog_gd
                this.fog.beginPath();
                this.fog.arc(x, y, 150, 0, 2*Math.PI);
                this.fog.closePath()
                this.fog.fill();

                this.fog.globalCompositeOperation = this.dark.globalCompositeOperation = this.main.globalCompositeOperation
            }*/
            zoomIn: function () {
                if (this.ratio < 2.3) {
                    this.ratio = Number(this.ratio) + 0.01
                }
            },
            zoomOut: function () {
                if (this.ratio > 0.1) {
                    this.ratio = Number(this.ratio) - 0.01
                }
            }
        },
        mounted() {
            /*this.main = this.$refs.main.getContext("2d");
            this.fog = this.$refs.fog.getContext("2d");
            this.dark = this.$refs.fog.getContext("2d");*/


            const postUrl = new URL(process.env.MERCURE_PUBLIC_URL);
            postUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/token/post');

            const postEs = new EventSource(postUrl);
            postEs.onmessage = e => {
                let data = JSON.parse(e.data)
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
                this.updateToken({
                    id: data.id,
                    width: data.width,
                    height: data.height,
                    top: data.topPosition,
                    left: data.leftPosition,
                    zIndex: data.zIndex,
                    users: data.users
                })
            }

            const deleteUrl = new URL(process.env.MERCURE_PUBLIC_URL);
            deleteUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/token/remove');

            const deleteEs = new EventSource(deleteUrl);
            deleteEs.onmessage = e => {
                let data = JSON.parse(e.data)
                this.removeTokenOnMap({
                    id: data.id,
                    mercure: true
                })
            }

            this.$refs.editorWrapper.scrollTop = 2048
            this.$refs.editorWrapper.scrollLeft = 2048
        }
    }
</script>

<style scoped>
    .editor-wrapper {
        position: relative;
        overflow: scroll;
        height: 100%;
        width: calc(100dvw - 300px);
        background-color: #E2E2E2;
    }

    .editor-wrapper::-webkit-scrollbar {
        width: .4em;
        height: .4em;
    }
    
    .editor-wrapper::-webkit-scrollbar-thumb {
        background-color: #666666;
    }

    .editor {
        position: relative;
        display: inline-block;
        background-color: #FFF;
        overflow: hidden;
        margin: 2048px;
    }

    .editor picture {
        max-width: 1200px;
    }

    .editor img {
        max-width: 1200px;
    }

    .editor-zoom {
        position: fixed;
        top: 48px;
        right: 300px;
        z-index: 4;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 80px;
    }

    .editor-zoom-add-btn, .editor-zoom-minus-btn {
        padding: 0;
        width: 28px;
        height: 28px;
        font-weight: 700;
        font-size: 1.2rem;
        color: #FFF;
        background-color: #D68836;
        border: none;
        border-radius: 0;
        cursor: pointer;
    }

    .editor-zoom-add-btn  {
        margin-bottom: -12px;
    }

    .editor-zoom-minus-btn {
        margin-top: -12px;
    }

    .editor-zoom-ratio {
        width: 28px;
        height: 28px;
        font-weight: 700;
        text-align: center;
        color: #FFF;
        background-color: #D68836;
        margin-bottom: 4px;
    }

    .editor-zoom-bar {
        display: block;
        transform: rotate(-90deg);
        margin: 80px 0;
        accent-color: #D68836;
    }

    canvas {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 100;
    }
</style>