<template>
    <div class="editor-wrapper" id="editor-wrapper" ref="editorWrapper" @mousedown="onMouseDown" @mouseup="onMouseUp" @wheel="onWheel" @mouseleave="onMouseUp" @contextmenu="onContextMenu">
        <div class="editor" id="editor" ref="map" :style="{ width: map.width + 'px', height: map.height + 'px', transform: 'scale(' + ratio + ')'}">
            <div v-if="map.hasDynamicLight && !isGameMaster">
                <canvas ref="main" id="main" :width="map.width" :height="map.height"></canvas>
                <canvas ref="fog" id="fog" :width="map.width" :height="map.height"></canvas>
                <canvas ref="dark" id="dark" :width="map.width" :height="map.height"></canvas>
                <TokenComposent :id="token.id" :key="key" v-for="(token, key) in map.tokens"></TokenComposent>
            </div>
            <div v-else>
                <canvas ref="main" id="main" v-on="{ mousedown: getOnDrawing ? drawStart : null }" :width="map.width" :height="map.height"></canvas>
                <TokenComposent :id="token.id" :key="token.id" v-for="token in map.tokens"></TokenComposent>
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
import TokenComposent from './token.vue'
import { mapActions, mapGetters } from 'vuex';
import { Token } from '../entity/token';

    export default defineComponent({
        components: {
            TokenComposent
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
                main: null as CanvasRenderingContext2D | null,
                walls: [] as {
                    start: { x: number, y: number }
                    end: { x: number, y: number}
                }[],
                drawingWall: {} as {
                    start: { x: number, y: number }
                    end: { x: number, y: number}
                }
            }
        },
        computed: {
            ...mapGetters('map', [
                'map',
                'getRatio',
                'getControllableTokens',
                'getOnDrawing'
            ]),
            ...mapGetters('user', [
                'isGameMaster',
                'getUserId'
            ])
        },
        watch: {
            map: {
                handler() {
                    if (this.map.hasDynamicLight && !this.isGameMaster) {
                        this.draw();
                    }
                },
                flush: 'post'
            }
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
                if(this.map.hasDynamicLight && this.fog && this.dark && !this.isGameMaster) {
                    let tokens = this.getControllableTokens(this.getUserId);
                    
                    this.fog!.clearRect(0, 0, this.map.width, this.map.height)
                    this.dark!.clearRect(0, 0, this.map.width, this.map.height)
                    this.main!.clearRect(0, 0, this.map.width, this.map.height)
                    
                    this.fog!.globalAlpha = 1;
                    this.fog!.fillStyle = 'black';
                    this.fog!.fillRect(0, 0, this.map.width, this.map.height);
                    this.fog!.globalCompositeOperation = 'destination-out';

                    tokens.forEach((token: Token) => {
                        let x = token.leftPosition! + token.width! / 2;
                        let y = token.topPosition! + token.height! / 2;
                        let fog_gd = this.fog!.createRadialGradient(x, y, 150, x, y, 300)
                        fog_gd.addColorStop(0, 'rgba(0, 0, 0, 1)');
                        fog_gd.addColorStop(.2, 'rgba(0, 0, 0, .4)');
                        fog_gd.addColorStop(1, 'rgba(0, 0, 0, 0)');
                        this.fog!.fillStyle = fog_gd
                        this.fog!.beginPath();
                        this.fog!.arc(x, y, 600, 0, 2*Math.PI);
                        this.fog!.closePath()
                        this.fog!.fill();
                    });

                    this.fog!.globalCompositeOperation = this.dark!.globalCompositeOperation = this.main!.globalCompositeOperation;
                }
            },
            drawGameMasterVue: function () {
                this.main!.clearRect(0, 0, this.map.width, this.map.height);
                this.main!.strokeStyle = "red";
                this.main!.lineWidth = 3;
                this.main!.beginPath();
                this.main!.moveTo(this.drawingWall.start.x, this.drawingWall.start.y);
                this.main!.lineTo(this.drawingWall.end.x, this.drawingWall.end.y);
                this.main!.stroke();
                this.walls.forEach((wall) => {
                    this.main!.beginPath();
                    this.main!.moveTo(wall.start.x, wall.start.y);
                    this.main!.lineTo(wall.end.x, wall.end.y);
                    this.main!.stroke();
                })
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
            },
            drawStart: function(e: MouseEvent) {
                if (e.button === 0) {
                    this.drawingWall = {
                        start: { x: e.offsetX, y: e.offsetY },
                        end: { x: e.offsetX, y: e.offsetY },
                    };
                    document.addEventListener("mousemove", this.drawUpdate)
                    document.addEventListener("mouseup", this.drawEnd)
                }
            },
            drawUpdate: function (e: MouseEvent) {
                this.drawingWall.end = {
                    x: e.offsetX,
                    y: e.offsetY,
                };
                this.emitter.emit("drawWall");
            },
            drawEnd: function () {
                document.removeEventListener("mousemove", this.drawUpdate);
                this.walls.push(this.drawingWall);
                this.emitter.emit("drawWall");
            }
        },
        mounted() {
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
                this.main = (this.$refs.main as HTMLCanvasElement).getContext("2d");
                if (this.map.hasDynamicLight && !this.isGameMaster) {
                    this.fog = (this.$refs.fog as HTMLCanvasElement).getContext("2d");
                    this.dark = (this.$refs.fog as HTMLCanvasElement).getContext("2d");
                    this.draw()
                }
            })

            this.emitter.on('isMoving', () => {
                this.draw();
            })

            this.emitter.on("drawWall", () => {
                this.drawGameMasterVue();
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