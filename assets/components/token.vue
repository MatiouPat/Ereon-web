<template>
    <div v-if="isGameMaster && token.layer == getLayer || isPropertiesContexting" class="token" ref="token" :style="{top: token.topPosition + 'px', left: token.leftPosition + 'px', width: token.width + 'px', height: token.height + 'px', zIndex: token.zIndex}" @mousedown.prevent="move" @contextmenu="showActions">
        <div class="resizers" :class="{isResizing: isResizing}">
            <div class="resizer top-left" @mousedown.stop="resize"></div>
            <div class="resizer top-middle" @mousedown.stop="resize"></div>
            <div class="resizer top-right" @mousedown.stop="resize"></div>
            <div class="resizer middle-left" @mousedown.stop="resize"></div>
            <div class="resizer middle-right" @mousedown.stop="resize"></div>
            <div class="resizer bottom-left" @mousedown.stop="resize"></div>
            <div class="resizer bottom-middle" @mousedown.stop="resize"></div>
            <div class="resizer bottom-right" @mousedown.stop="resize"></div>
        </div>
        <ul class="context-menu" :class="{isContexting: isContexting}">
            <li @click="upZIndex">Vers l'avant</li>
            <li @click="downZIndex">Vers l'arrière</li>
            <li @click="showProperties">Propriétés</li>
        </ul>
        <Teleport to="#editor">
            <div v-if="isPropertiesContexting" class="modal-wrapper">
                <div class="modal-box">
                    <form>
                        <div class="form-part">
                            <h3>Positionnement</h3>
                            <div class="row">
                                <div class="form-group">
                                    <label class="form-label">Width</label>
                                    <input class="form-control" :value="token.width" type="number" @input="setTokenWidth">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Height</label>
                                    <input class="form-control" :value="token.height" type="number" @input="setTokenHeight">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="form-label">Top</label>
                                    <input class="form-control" :value="token.topPosition" type="number" @input="setTokenTop">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Left</label>
                                    <input class="form-control" :value="token.leftPosition" type="number" @input="setTokenLeft">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="form-label">Zindex</label>
                                    <input class="form-control" :value="token.zIndex" type="number" @input="setTokenZIndex">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Layer</label>
                                    <select class="form-control" :value="token.layer" @input="setTokenLayer">
                                        <option :value="1">Map</option>
                                        <option :value="2">Token</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-part" v-if="getPlayers.length">
                            <h3>Contrôle</h3>
                            <div v-for="player in getPlayers" :key="player.id">
                                <label>{{ player.username }}</label>
                                <input type="checkbox" :value="player.id" :checked="canControlledBy(player.id, token.id)" @change="addTokenPlayer">
                            </div>
                        </div>
                        <div class="form-part" v-else >
                            <h3>Contrôle</h3>
                            <span>Aucun joueur présent sur cette partie</span>
                        </div>
                        <button type="button" class="btn btn-primary" @click="submitForm">Valider</button>
                    </form>
                </div>
            </div>
        </Teleport>
        <img :src="'/uploads/images/' + token.asset.image.imageName" alt="Map">
    </div>
    <div v-else-if="!isGameMaster && canControlledBy(getUserId, id)" class="token" ref="token" style="z-index: 20;" :style="{top: token.topPosition + 'px', left: token.leftPosition + 'px', width: token.width + 'px', height: token.height + 'px'}" @mousedown.prevent="move">
        <img :src="'/uploads/images/' + token.asset.image.imageName" alt="Map">
    </div>
    <div v-else class="token" ref="token" :style="{top: token.topPosition + 'px', left: token.leftPosition + 'px', width: token.width + 'px', height: token.height + 'px', zIndex: token.zIndex}">
        <img :src="'/uploads/images/' + token.asset.image.imageName" alt="Map">
    </div>
</template>

<script lang="ts">
import { defineComponent, inject, InputHTMLAttributes } from 'vue';
import { mapActions, mapGetters } from 'vuex';
import { Token } from '../entity/token';
import { User } from '../entity/user';

    export default defineComponent({
        data() {
            return {
                emitter: inject('emitter') as any,
                $store: inject('store') as any,
                /**
                 * The token being resized
                 */
                resizer: undefined as HTMLElement | undefined,
                /**
                 * If you need to display resizers to resize the token
                 */
                isResizing: false as boolean,
                /**
                 * If you need to display the context box
                 */
                isContexting: false as boolean,
                /**
                 * If you need to display token parameters
                 */
                isPropertiesContexting: false as boolean,
                startX: 0 as number,
                startY: 0 as number,
                startWidth: 0 as number,
                startHeight: 0 as number,
                startMouseX: 0 as number,
                startMouseY: 0 as number
            }
        },
        props: [
            'id'
        ],
        computed: {
            ...mapGetters('map', [
                'map',
                'getTokenById',
                'canControlledBy',
                'getIndexOfUser',
                'getRatio',
                'getLayer'
            ]),
            ...mapGetters('user', [
                'getUserId',
                'isGameMaster',
                'getPlayers'
            ]),
            /**
             * The token to display
             */
            token(): Token {
                return this.getTokenById(this.id)
            }
        },
        methods: {
            ...mapActions('map', [
                'removeTokenOnMap',
                'updateToken',
                'finishUpdateToken',
                'changeZIndex'
            ]),
            /**
             * Show contextual box
             * @param {*} e 
             */
            showActions: function (e: MouseEvent) {
                this.isContexting = true;
                (this.$refs.token as HTMLElement).addEventListener('mouseleave', () => {
                    this.isContexting = false;
                }, {once: true})
            },
            /**
             * Putting the token a little further forward
             */
            upZIndex: function () {
                this.changeZIndex({
                    id: this.token.id,
                    zIndex: this.token.zIndex! + 1
                })
                this.isResizing = false;
                this.isContexting = false;
            },
            /**
             * Put the token a little further back
             */
            downZIndex: function () {
                this.changeZIndex({
                    id: this.token.id,
                    zIndex: this.token.zIndex! - 1
                })
                this.isResizing = false;
                this.isContexting = false;
            },
            /**
             * Display token parameters
             */
            showProperties: function () {
                this.isPropertiesContexting = true
                this.isResizing = false;
                this.isContexting = false;
            },
            /**
             * Start moving the token on the map by left-clicking on it
             * @param {*} e 
             */
            move: function (e: MouseEvent) {
                if(e.button === 0) {
                    this.isResizing = true;
                    this.startX = (e.screenX / this.getRatio) - (this.$refs.token as HTMLElement).offsetLeft
                    this.startY = (e.screenY / this.getRatio) - (this.$refs.token as HTMLElement).offsetTop
                    document.addEventListener('keydown', this.removeToken)
                    document.addEventListener('mousemove', this.onMove)
                    document.addEventListener('mouseup', () => {
                        document.removeEventListener('mousemove', this.onMove)
                        this.finishUpdateToken(this.token)
                    }, {once: true})
                }
                if (this.isResizing || this.isContexting) {
                    window.addEventListener('mousedown', this.clickOutside)
                }
            },
            /**
             * Moving the token on the map
             * @param {*} e 
             */
            onMove: function(e: MouseEvent) {
                let left = this.token.leftPosition
                let top = this.token.topPosition
                if ((e.screenX / this.getRatio) - this.startX > -this.token.width!/2 && (e.screenX / this.getRatio) - this.startX < this.map.width - this.token.width!/2) {
                    left = (e.screenX / this.getRatio) - this.startX
                }
                if ((e.screenY / this.getRatio) - this.startY > -this.token.height!/2 && (e.screenY / this.getRatio) - this.startY < this.map.height - this.token.height!/2) {
                    top = (e.screenY / this.getRatio) - this.startY
                }
                let token = this.token;
                token.leftPosition = Math.floor(left!);
                token.topPosition = Math.floor(top!);
                this.updateToken(token);
                this.emitter.emit('isMoving');
            },
            /**
             * Start token resizing after clicking on a resizer
             * @param {*} e 
             */
            resize: function(e: MouseEvent) {
                this.resizer = e.target as HTMLElement
                this.startWidth = this.token.width!;
                this.startHeight = this.token.height!;
                this.startX = this.token.leftPosition!;
                this.startY = this.token.topPosition!;
                this.startMouseX = e.pageX;
                this.startMouseY = e.pageY;
                document.addEventListener('mousemove', this.onResize)
                document.addEventListener('mouseup', () => {
                    this.finishUpdateToken(this.token)
                    document.removeEventListener('mousemove', this.onResize)
                }, { once: true })
            },
            /**
             * Resize token
             * @param {*} e 
             */
            onResize: function(e: MouseEvent) {
                let width = this.startWidth
                let height = this.startHeight
                let top = this.startY
                let left = this.startX
                if (this.resizer!.classList.contains('bottom-right')) {
                    let diff = (this.startWidth + (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth + (e.pageX - this.startMouseX)
                    height = this.startHeight + diff
                }else if (this.resizer!.classList.contains('middle-right')) {
                    width =  this.startWidth + (e.pageX - this.startMouseX)
                }else if (this.resizer!.classList.contains('bottom-middle')) {
                    height = this.startHeight + (e.pageY - this.startMouseY)
                }else if (this.resizer!.classList.contains('top-right')) {
                    let diffScale = (this.startWidth + (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth + (e.pageX - this.startMouseX)
                    height = this.startHeight + diffScale; 
                    top = this.startX - (e.pageX - this.startMouseX);
                }else if (this.resizer!.classList.contains('bottom-left')) {
                    let diffScale = (this.startWidth - (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth - (e.pageX - this.startMouseX)
                    height = this.startHeight + diffScale; 
                    left = this.startX + (e.pageX - this.startMouseX)
                }else if (this.resizer!.classList.contains('middle-left')) {
                    width = this.startWidth - (e.pageX - this.startMouseX)
                    left = this.startX + (e.pageX - this.startMouseX)
                }else if (this.resizer!.classList.contains('top-middle')) {
                    height = this.startHeight - (e.pageY - this.startMouseY); 
                    top = this.startY + (e.pageY - this.startMouseY);
                }else if (this.resizer!.classList.contains('top-left')) {
                    let diffScale = (this.startWidth - (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth - (e.pageX - this.startMouseX)
                    height = this.startHeight + diffScale; 
                    let diffPosition = (this.startX + (e.pageX - this.startMouseX)) - this.startX
                    left = this.startX + (e.pageX - this.startMouseX)
                    top = this.startY + diffPosition;
                }
                let token = this.token;
                token.topPosition = top;
                token.leftPosition = left;
                token.width = width;
                token.height = height;
                this.updateToken(token)
            },
            /**
             * 
             * @param {*} e 
             */
            removeToken: function(e: KeyboardEvent) {
                if (e.key === "Delete") {
                    this.isResizing = false;
                    this.isContexting = false;
                    document.removeEventListener('keydown', this.removeToken)
                    this.removeTokenOnMap({id: this.token.id, mercure: false})
                }
            },
            /**
             * Hide context box when clicked outside it
             * @param {*} e 
             */
            clickOutside: function(e: MouseEvent) {
                if(!this.$el.contains(e.target)){
                    window.removeEventListener('click', this.clickOutside)
                    this.isResizing = false;
                    this.isContexting = false;
                    document.removeEventListener('keydown', this.removeToken)
                }
            },
            setTokenWidth: function(e: Event) {
                this.$store.commit('map/setTokenWidth', {
                    token: this.token,
                    width: Number((e.target as InputHTMLAttributes).value)
                })
            },
            setTokenHeight: function(e: Event) {
                this.$store.commit('map/setTokenHeight', {
                    token: this.token,
                    height: Number((e.target as InputHTMLAttributes).value)
                })
            },
            setTokenTop: function(e: Event) {
                this.$store.commit('map/setTokenTop', {
                    token: this.token,
                    topPosition: Number((e.target as InputHTMLAttributes).value)
                })
            },
            setTokenLeft: function(e: Event) {
                this.$store.commit('map/setTokenLeft', {
                    token: this.token,
                    leftPosition: Number((e.target as InputHTMLAttributes).value)
                })
            },
            setTokenZIndex: function(e: Event) {
                this.$store.commit('map/setTokenZIndex', {
                    token: this.token,
                    zIndex: Number((e.target as InputHTMLAttributes).value)
                })
            },
            setTokenLayer: function(e: Event) {
                this.$store.commit('map/setTokenLayer', {
                    token: this.token,
                    layer: Number((e.target as InputHTMLAttributes).value)
                })
            },
            addTokenPlayer: function(e: Event) {
                if((e.target! as InputHTMLAttributes).checked) {
                    this.$store.commit('map/addTokenPlayer', {
                        token: this.token,
                        user: { id: (e.target! as InputHTMLAttributes).value }
                    })
                }else {
                    this.$store.commit('map/removeTokenPlayer', {
                        token: this.token,
                        user: { id: this.getIndexOfUser({ id: (e.target! as InputHTMLAttributes).value}, this.id) }
                    })
                }
            },
            /**
             * Change token settings after form submission
             */
            submitForm: function() {
                let users: string[] = []
                this.token.users.forEach((user: User) => {
                    users.push('api/users/' + user.id.toString())
                });
                this.finishUpdateToken({
                    id: Number(this.token.id),
                    width: Number(this.token.width),
                    height: Number(this.token.height),
                    leftPosition: Number(this.token.leftPosition),
                    topPosition: Number(this.token.topPosition),
                    zIndex: Number(this.token.zIndex),
                    users: users
                })
                this.isPropertiesContexting = false
            }
        },
        mounted() {
            const url = new URL(process.env.MERCURE_PUBLIC_URL!);
            url.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/tokens/' + this.id);
            const updateEs = new EventSource(url);
            updateEs.onmessage = e => {
                if (e.data != "") {
                    this.updateToken(JSON.parse(e.data))
                    this.emitter.emit('isMoving');
                } else {
                    this.removeTokenOnMap({
                        id: this.id,
                        mercure: true
                    })
                }
            }
            updateEs.onerror = err => {
                console.error(err)
            }
        },
    })
</script>

<style scoped>
    .token {
        position: absolute;
        cursor: pointer;
    }

    .resizers{
        display: none;
        width: 100%;
        height: 100%;
        border: 1px solid #4286f4;
    }

    .resizers.isResizing {
        display: block;
    }

    .resizers .resizer{
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: white;
        border: 3px solid #4286f4;
        position: absolute;
        z-index: 2;
    }

    .resizers .resizer.top-left {
        left: -5px;
        top: -5px;
        cursor: nwse-resize;
    }.resizers .resizer.top-middle {
        left: calc(50% - 5px);
        top: -5px;
        cursor: ns-resize;
    }.resizers .resizer.top-right {
        right: -5px;
        top: -5px;
        cursor: nesw-resize;
    }.resizers .resizer.middle-left {
        left: -5px;
        top: calc(50% - 5px);
        cursor: ew-resize;
    }.resizers .resizer.middle-right {
        right: -5px;
        top: calc(50% - 5px);
        cursor: ew-resize;
    }.resizers .resizer.bottom-left {
        left: -5px;
        bottom: -5px;
        cursor: nesw-resize;
    }.resizers .resizer.bottom-middle {
        left: calc(50% - 5px);
        bottom: -5px;
        cursor: ns-resize;
    }.resizers .resizer.bottom-right {
        right: -5px;
        bottom: -5px;
        cursor: nwse-resize;
    }

    .context-menu {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 10;
        display: none;
    }

    .context-menu.isContexting {
        display: block;
    }

    .context-menu li {
        background-color: #FFF;
        border: solid 1px #000;
        padding: 2px 4px;
        cursor: pointer;
    }

    .context-menu li:hover {
        background-color: #AAA;
    }

    img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .modal-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 10;
        display: flex;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.75);
    }

    .modal-box {
        display: block;
        width: 400px;
        margin: auto;
        padding: 16px;
        background-color: #FFF;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 24px;
    }

    .form-part {
        width: 100%;
    }

    .row {
        display: flex;
        gap: 8px;
    }

    .field {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #090D11;
        margin-bottom: 4px;
    }

    .dark .context-menu li {
        background-color: #2b2a33;
    }

    .context-menu li:hover {
        background-color: #1c1b22;
    }

    .dark .modal-box {
        background-color: #2b2a33;
    }

    .dark h3 {
        color: #F3F4F4;
    }
</style>