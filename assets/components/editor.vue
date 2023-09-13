<template>
    <div class="editor-wrapper" id="editor-wrapper" ref="editorWrapper" @mousedown="onMouseDown" @mouseup="onMouseUp" @wheel="onWheel" @mouseleave="onMouseUp" @contextmenu="onContextMenu">
        <div class="editor" id="editor" ref="map" :style="{ width: map.width + 'px', height: map.height + 'px', transform: 'scale(' + ratio + ')'}">
            <div v-if="map.hasDynamicLight">
                <canvas ref="main" id="main" :width="map.width" :height="map.height"></canvas>
                <canvas ref="fog" id="fog" :width="map.width" :height="map.height"></canvas>
                <canvas ref="dark" id="dark" :width="map.width" :height="map.height"></canvas>
                <Token :id="token.id" @is-moving="draw" :key="token.id" v-for="token in map.tokens"></Token>
            </div>
            <div v-else>
                <Token :id="token.id" :key="token.id" v-for="token in map.tokens"></Token>
            </div>
        </div>
        <div class="editor-zoom">
            <span class="editor-zoom-ratio">{{(ratio * 100).toFixed(0)}}</span>
            <button class="editor-zoom-add-btn" @mousedown="zoomIn">+</button>
            <input class="editor-zoom-bar" type="range" min="0.1" max="2.3" v-model="ratio" step="0.01" @change="updateRatio">
            <button class="editor-zoom-minus-btn" @mousedown="zoomOut">-</button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, inject } from 'vue';
import Token from './token.vue'
import { mapActions, mapGetters } from 'vuex';

    export default defineComponent({
        components: {
            Token
        },
        data() {
            return {
                emitter: inject('emitter') as any,
                /**
                 * The zoom in on the map
                 */
                ratio: 1 as number,
                /**
                 * The margin around the map
                 */
                margin: 200 as number,
                /**
                 * The X position where the user begins scrolling
                 */
                startX: 0 as number,
                /**
                 * The Y position where the user begins scrolling
                 */
                startY: 0 as number,
                /**
                 * The X position at which the user begins scrolling in relation to the map
                 */
                mapX: 0 as number,
                /**
                 * The Y position at which the user begins scrolling in relation to the map
                 */
                mapY: 0 as number,
                fog: null as CanvasRenderingContext2D | null,
                dark: null as CanvasRenderingContext2D | null,
                main: null as CanvasRenderingContext2D | null
            }
        },
        computed: {
            ...mapGetters('map', [
                'map',
                'getRatio'
            ])
        },
        methods: {
            ...mapActions('map', [
                'addTokenOnMap',
                'updateToken',
                'removeTokenOnMap',
                'setRatio'
            ]),
            /**
             * Starts scrolling the map after right-clicking
             * @param {*} e 
             */
            onMouseDown: function (e: MouseEvent) {
                if(e.button === 2 || e.button === 1) {
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
            onMouseMove: function (e: MouseEvent) {
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
            onWheel: function (e: WheelEvent) {
                e.preventDefault();
                if(e.ctrlKey == true){
                    if (this.ratio - e.deltaY * 0.0005 <= 0.1) {
                        this.ratio = 0.1;
                    } else if (this.ratio - e.deltaY * 0.00045 >= 2.3) {
                        this.ratio = 2.3;
                    } else if (this.ratio >= 0.1 && this.ratio <= 2.3) {
                        this.ratio = this.ratio - e.deltaY * 0.0005;
                    }
                    this.updateRatio();
                }
            },
            /**
             * Avoid right-click context menus
             * @param {*} e 
             */
            onContextMenu: function (e: MouseEvent) {
                e.preventDefault();
            },
            draw: function () {
                let x = this.map.tokens[0].left
                let y = this.map.tokens[0].top

                this.fog!.clearRect(0, 0, this.map.width, this.map.height)
                this.dark!.clearRect(0, 0, this.map.width, this.map.height)

                this.fog!.globalAlpha = 1;
                this.fog!.fillStyle = 'black';
                this.fog!.fillRect(0, 0, this.map.width, this.map.height);
                this.fog!.globalCompositeOperation = 'destination-out';
                let fog_gd = this.fog!.createRadialGradient(x, y, 600, x, y, 0)
                fog_gd.addColorStop(0, 'rgba(255, 255, 255, 0)');
                fog_gd.addColorStop(1, 'rgba(255, 255, 255, 1)');
                this.fog!.fillStyle = fog_gd
                this.fog!.beginPath();
                this.fog!.arc(x, y, 400, 0, 2*Math.PI);
                this.fog!.closePath()
                this.fog!.fill();

                this.fog!.globalCompositeOperation = this.dark!.globalCompositeOperation = this.main!.globalCompositeOperation
            },
            zoomIn: function () {
                if (this.ratio < 2.3) {
                    this.ratio = Number(this.ratio) + 0.01
                    this.updateRatio()
                }
            },
            zoomOut: function () {
                if (this.ratio > 0.1) {
                    this.ratio = Number(this.ratio) - 0.01
                    this.updateRatio()
                }
            },
            updateRatio: function () {
                this.setRatio(this.ratio)
            }
        },
        mounted() {
            if (this.map.hasDynamicLight) {
                this.main = (this.$refs.main as HTMLCanvasElement).getContext("2d");
                this.fog = (this.$refs.fog as HTMLCanvasElement).getContext("2d");
                this.dark = (this.$refs.fog as HTMLCanvasElement).getContext("2d");
            }

            const url = new URL(process.env.MERCURE_PUBLIC_URL!);
            url.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/tokens');

            const postEs = new EventSource(url);
            postEs.onmessage = e => {
                let data = JSON.parse(e.data)
                this.addTokenOnMap({
                    mercure: true,
                    data: data
                })
            }

            (this.$refs.editorWrapper as HTMLElement).scrollTop = 2048;
            (this.$refs.editorWrapper as HTMLElement).scrollLeft = 2048;

            this.emitter.on('isDownload', () => {
                if (this.map.hasDynamicLight) {
                    this.draw()
                }
            })
        }
    })
</script>

<style scoped>
    .editor-wrapper {
        position: relative;
        overflow: scroll;
        height: 100%;
        width: calc(100dvw - 348px);
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
        z-index: 5;
    }

    .editor-zoom-minus-btn {
        margin-top: -12px;
        z-index: 5;
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
        width: auto;
    }

    canvas {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 10;
    }

    .dark .editor-wrapper {
        background-color: #131313;
    }

    .dark .editor {
        background-color: #2b2a33;
    }
</style>