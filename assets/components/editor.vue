<template>
    <div class="editor-wrapper" id="editor-wrapper" ref="editorWrapper" @mousedown="onMouseDown" @mouseup="onMouseUp" @wheel="onWheel" @mouseleave="onMouseUp" @contextmenu="onContextMenu">
        <div class="editor" id="editor" ref="map" :style="{ width: map.width + 'px', height: map.height + 'px', transform: 'scale(' + ratio + ')'}">
            <div v-if="map.hasDynamicLight && !isGameMaster">
                <canvas ref="fog" id="fog" :width="map.width" :height="map.height" style="z-index: 15;"></canvas>
                <TokenComposent :id="token.id" :key="key" v-for="(token, key) in map.tokens"></TokenComposent>
            </div>
            <div v-else>
                <canvas ref="main" id="main" v-on="{ mousedown: getOnDrawing ? drawStart : null }" :width="map.width" :height="map.height" :style="{zIndex: getLayer === 3 ? 10 : -100}"></canvas>
                <TokenComposent :id="token.id" :key="key" v-for="(token, key) in map.tokens"></TokenComposent>
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
import * as twgl from 'twgl.js';
import lightVertSrc from "../shaders/light.vert";
import lightFragSrc from "../shaders/light.frag";
import shadowVertSrc from "../shaders/shadow.vert";
import shadowFragSrc from "../shaders/shadow.frag";
import sceneVertSrc from "../shaders/scene.vert";
import sceneFragSrc from "../shaders/scene.frag";
import { calculateGeometry } from "../geometry";
import { LightingWallService } from '../services/lightingwallService';
import { LightingWall } from '../entity/lightingwall';

    export default defineComponent({
        components: {
            TokenComposent
        },
        data() {
            return {
                emitter: inject('emitter') as any,
                lightWallService: new LightingWallService as LightingWallService,
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
                fog: null as WebGLRenderingContext | null,
                dark: null as CanvasRenderingContext2D | null,
                main: null as CanvasRenderingContext2D | null,
                drawingWall: {} as LightingWall,
                lightTexture: {} as WebGLTexture,
                sceneTexture: {} as WebGLTexture
            }
        },
        computed: {
            ...mapGetters('map', [
                'map',
                'getRatio',
                'getLayer',
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
                'setRatio',
                'addLightingWall'
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
            draw: async function () {
                if(this.map.hasDynamicLight && this.fog && !this.isGameMaster) {
                    const quadArrays = {
                        vertex: {
                        numComponents: 2,
                        data: new Float32Array([
                            -1, -1, -1, 1, 1, -1,
                            -1, 1, 1, -1, 1, 1,
                        ]),
                        },
                    };
                    const shadowProgram = twgl.createProgramInfo(this.fog, [shadowVertSrc, shadowFragSrc]);
                    const lightProgram = twgl.createProgramInfo(this.fog, [lightVertSrc, lightFragSrc]);
                    const sceneProgram = twgl.createProgramInfo(this.fog, [sceneVertSrc, sceneFragSrc]);
                    const arrays = {
                        vertex: {
                        numComponents: 2,
                        data: [],
                        },
                    };
                    const shadowBuffer = twgl.createBufferInfoFromArrays(this.fog, arrays);
                    const quadBuffer = twgl.createBufferInfoFromArrays(this.fog, quadArrays);
                    const attachments = [
                        {
                            format: this.fog.RGBA,
                            type: this.fog.UNSIGNED_BYTE,
                            min: this.fog.LINEAR,
                            wrap: this.fog.CLAMP_TO_EDGE
                        }
                    ]
                    const shadowFramebuffer = twgl.createFramebufferInfo(this.fog, attachments);
                    const lightFramebuffer = twgl.createFramebufferInfo(this.fog, attachments);
                    let tokens = this.getControllableTokens(this.getUserId);

                    twgl.bindFramebufferInfo(this.fog, shadowFramebuffer)
                    
                    let x = tokens[0].leftPosition! + tokens[0].width! / 2;
                    let y = tokens[0].topPosition! + tokens[0].height! / 2;
                    let coord = this.canvasToGlCoords(x, y)

                    // Draw shadows
                    const vertices = [] as number[];
                    for (const wall of this.map.lightingWalls) {
                        vertices.push(
                            ...calculateGeometry({
                                light: coord,
                                a: this.canvasToGlCoords(wall.startX, wall.startY),
                                b: this.canvasToGlCoords(wall.endX, wall.endY),
                                lightRadius: 3,
                            })
                        );
                    }

                    twgl.setAttribInfoBufferFromArray(
                        this.fog,
                        shadowBuffer.attribs!.vertex,
                        vertices
                    );
                    shadowBuffer.numElements = vertices.length / 2;

                    this.fog.enable(this.fog.BLEND);
                    this.fog.blendFunc(this.fog.SRC_ALPHA, this.fog.ONE_MINUS_SRC_ALPHA);

                    this.fog.useProgram(shadowProgram.program);
                    twgl.setBuffersAndAttributes(this.fog, shadowProgram, shadowBuffer);
                    twgl.drawBufferInfo(this.fog, shadowBuffer);

                    // Draw lights
                    twgl.bindFramebufferInfo(this.fog, lightFramebuffer);
                    this.fog.clearColor(0.3, 0.3, 0.3, 1.0);
                    this.fog.clear(this.fog.COLOR_BUFFER_BIT);
                    this.fog.enable(this.fog.BLEND);
                    this.fog.blendFunc(this.fog.SRC_ALPHA, this.fog.DST_ALPHA);

                    this.fog.useProgram(lightProgram.program);
                    twgl.setBuffersAndAttributes(this.fog, lightProgram, quadBuffer);
                    twgl.setUniforms(lightProgram, {
                        lightPosition: [coord.x, coord.y],
                        shadowTexture: shadowFramebuffer.attachments[0],
                        lightTexture: this.lightTexture,
                    });
                    twgl.drawBufferInfo(this.fog, quadBuffer);

                    // Draw scene
                    this.fog.bindFramebuffer(this.fog.FRAMEBUFFER, null);
                    this.fog.clearColor(0.0, 0.0, 0.0, 1.0);
                    this.fog.clear(this.fog.COLOR_BUFFER_BIT);
                    this.fog.enable(this.fog.BLEND);
                    this.fog.blendFunc(this.fog.SRC_ALPHA, this.fog.DST_ALPHA);

                    this.fog.useProgram(sceneProgram.program);
                    twgl.setBuffersAndAttributes(this.fog, sceneProgram, quadBuffer);
                    twgl.setUniforms(sceneProgram, {
                        lightTexture: lightFramebuffer.attachments[0],
                        sceneTexture: this.sceneTexture,
                    });
                    this.fog.enable(this.fog.BLEND);
                    this.fog.blendFunc(this.fog.SRC_ALPHA, this.fog.DST_ALPHA);
                    twgl.drawBufferInfo(this.fog, quadBuffer);
                }
            },
            drawGameMasterVue: function () {
                this.main!.clearRect(0, 0, this.map.width, this.map.height);
                this.main!.strokeStyle = "red";
                this.main!.lineWidth = 3;
                this.main!.beginPath();
                this.main!.moveTo(this.drawingWall.startX!, this.drawingWall.startY!);
                this.main!.lineTo(this.drawingWall.endX!, this.drawingWall.endY!);
                this.main!.stroke();
                this.map.lightingWalls.forEach((wall: LightingWall) => {
                    this.main!.beginPath();
                    this.main!.moveTo(wall.startX!, wall.startY!);
                    this.main!.lineTo(wall.endX!, wall.endY!);
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
                    this.drawingWall.map = '/api/maps/' + this.map.id;
                    this.drawingWall.startX = e.offsetX;
                    this.drawingWall.startY = e.offsetY;
                    this.drawingWall.endX = e.offsetX;
                    this.drawingWall.endY = e.offsetY;
                    document.addEventListener("mousemove", this.drawUpdate)
                    document.addEventListener("mouseup", this.drawEnd)
                }
            },
            drawUpdate: function (e: MouseEvent) {
                this.drawingWall.endX = e.offsetX;
                this.drawingWall.endY = e.offsetY;
                this.emitter.emit("drawWall");
            },
            drawEnd: function () {
                document.removeEventListener("mousemove", this.drawUpdate);
                this.addLightingWall(JSON.parse(JSON.stringify(this.drawingWall)));
                this.emitter.emit("drawWall");
            },
            canvasToGlCoords: function (x: number, y: number) {
                return {
                    x: (x / this.map.width) * 2 - 1,
                    y: -((y / this.map.width) * 2 - 1),
                };
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
                if (!this.isGameMaster && this.map.hasDynamicLight) {
                    this.fog = (this.$refs.fog as HTMLCanvasElement).getContext("webgl");
                    this.sceneTexture = twgl.createTexture(this.fog!, {src: "./uploads/images/asset/taverne-01.webp", min: this.fog!.LINEAR, wrap: this.fog!.CLAMP_TO_EDGE})
                    this.lightTexture = twgl.createTexture(this.fog!, {src: "./build/images/lightsource.png"})
                    this.draw()
                }else if(this.isGameMaster && this.map.hasDynamicLight) {
                    this.main = (this.$refs.main as HTMLCanvasElement).getContext("2d");
                    this.drawGameMasterVue();
                }
            })

            this.emitter.on('isMoving', () => {
                if (!this.isGameMaster && this.map.hasDynamicLight) {
                    this.draw();
                }
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
    }

    .dark .editor-wrapper {
        background-color: #131313;
    }

    .dark .editor {
        background-color: #2b2a33;
    }
</style>