<template>
    <div v-if="isGameMaster" class="token" ref="token" :style="{top: token.top + 'px', left: token.left + 'px', width: token.width + 'px', height: token.height + 'px', zIndex: token.zIndex}" @mousedown="move" @contextmenu="showActions">
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
                                <div class="field">
                                    <label>Width</label>
                                    <input :value="token.width" type="number" @input="setTokenWidth">
                                </div>
                                <div class="field">
                                    <label>Height</label>
                                    <input :value="token.height" type="number" @input="setTokenHeight">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <label>Top</label>
                                    <input :value="token.top" type="number" @input="setTokenTop">
                                </div>
                                <div class="field">
                                    <label>Left</label>
                                    <input :value="token.left" type="number" @input="setTokenLeft">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <label>Zindex</label>
                                    <input :value="token.zIndex" type="number" @input="setTokenZIndex">
                                </div>
                            </div>
                        </div>
                        <div class="form-part" v-if="getPlayers.length">
                            <h3>Contrôle</h3>
                            <div v-for="player in getPlayers">
                                <label>{{ player.username }}</label>
                                <input type="checkbox" :value="player.id" :checked="canControlledBy(player.id, token.id)" @change="addTokenPlayer">
                            </div>
                        </div>
                        <div class="form-part" v-else >
                            <h3>Contrôle</h3>
                            <span>Aucun joueur présent sur cette partie</span>
                        </div>
                        <button type="button" class="btn" @click="submitForm">Valider</button>
                    </form>
                </div>
            </div>
        </Teleport>
        <picture>
            <source type="image/webp" :srcset="'/uploads/images/asset/' + token.compressedImage">
            <img :src="'/uploads/images/asset/' + token.image" alt="Map">
        </picture>
    </div>
    <div v-else class="token" ref="token" :style="{top: token.top + 'px', left: token.left + 'px', width: token.width + 'px', height: token.height + 'px', zIndex: token.zIndex}" @mousedown="move">
        <picture>
            <source type="image/webp" :srcset="'/uploads/images/asset/' + token.compressedImage">
            <img :src="'/uploads/images/asset/' + token.image" alt="Map">
        </picture>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

    export default {
        data() {
            return {
                resizer: null,
                isResizing: false,
                isContexting: false,
                isPropertiesContexting: false,
                startX: 0,
                startY: 0,
                startWidth: 0,
                startHeight: 0,
                startMouseX: 0,
                startMouseY: 0
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
                'getIndexOfUser'
            ]),
            ...mapGetters('user', [
                'getUserId',
                'isGameMaster',
                'getPlayers'
            ]),
            token() {
                return this.getTokenById(this.id)
            },
        },
        methods: {
            ...mapActions('map', [
                'removeTokenOnMap',
                'updateToken',
                'finishUpdateToken',
                'changeZIndex'
            ]),
            showActions: function (e) {
                e.preventDefault()
                this.isContexting = true;
            },
            upZIndex: function () {
                this.changeZIndex({
                    id: this.token.id,
                    zIndex: this.token.zIndex + 1
                })
                this.isResizing = false;
                this.isContexting = false;
            },
            downZIndex: function () {
                this.changeZIndex({
                    id: this.token.id,
                    zIndex: this.token.zIndex - 1
                })
                this.isResizing = false;
                this.isContexting = false;
            },
            showProperties: function () {
                this.isPropertiesContexting = true
                this.isResizing = false;
                this.isContexting = false;
            },
            /**
             * 
             * @param {*} e 
             */
            move: function (e) {
                if(this.isGameMaster || this.canControlledBy(this.getUserId, this.id)) {
                    e.preventDefault();
                    if(e.button === 0) {
                        this.isResizing = true;
                        this.startX = e.screenX - this.$refs.token.offsetLeft;
                        this.startY = e.screenY - this.$refs.token.offsetTop;
                        document.addEventListener('keydown', this.removeToken)
                        document.addEventListener('mousemove', this.onMove)
                        document.addEventListener('mouseup', () => {
                            this.finishUpdateToken({
                                id: this.token.id,
                                width: this.token.width,
                                height: this.token.height,
                                left: this.token.left,
                                top: this.token.top,
                                zIndex: this.token.zIndex
                            })
                            document.removeEventListener('mousemove', this.onMove)
                        }, { once: true })
                    }
                    window.addEventListener('mousedown', this.clickOutside)
                }
            },
            /**
             * 
             * @param {*} e 
             */
            onMove: function(e) {
                let left = this.token.left
                let top = this.token.top
                e.preventDefault();
                if (e.screenX - this.startX > -this.token.width/2 && e.screenX - this.startX < this.map.width - this.token.width/2) {
                    left += e.screenX - this.$refs.token.offsetLeft - this.startX
                }
                if (e.screenY - this.startY > -this.token.height/2 && e.screenY - this.startY < this.map.height - this.token.height/2) {
                    top += e.screenY - this.$refs.token.offsetTop - this.startY
                }
                this.updateToken({
                    id: this.token.id,
                    width: this.token.width,
                    height: this.token.height,
                    left: left,
                    top: top,
                    zIndex: this.token.zIndex
                })
            },
            /**
             * 
             * @param {*} e 
             */
            resize: function(e) {
                e.preventDefault()
                this.resizer = e.target
                this.startWidth = this.token.width;
                this.startHeight = this.token.height;
                this.startX = this.token.left;
                this.startY = this.token.top;
                this.startMouseX = e.pageX;
                this.startMouseY = e.pageY;
                document.addEventListener('mousemove', this.onResize)
                document.addEventListener('mouseup', () => {
                    this.finishUpdateToken({
                        id: this.token.id,
                        width: this.token.width,
                        height: this.token.height,
                        left: this.token.left,
                        top: this.token.top,
                        zIndex: this.token.zIndex
                    })
                    document.removeEventListener('mousemove', this.onResize)
                }, { once: true })
            },
            /**
             * 
             * @param {*} e 
             */
            onResize: function(e) {
                let width = this.startWidth
                let height = this.startHeight
                let top = this.startY
                let left = this.startX
                if (this.resizer.classList.contains('bottom-right')) {
                    let diff = (this.startWidth + (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth + (e.pageX - this.startMouseX)
                    height = this.startHeight + diff
                }else if (this.resizer.classList.contains('middle-right')) {
                    width =  this.startWidth + (e.pageX - this.startMouseX)
                }else if (this.resizer.classList.contains('bottom-middle')) {
                    height = this.startHeight + (e.pageY - this.startMouseY)
                }else if (this.resizer.classList.contains('top-right')) {
                    let diffScale = (this.startWidth + (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth + (e.pageX - this.startMouseX)
                    height = this.startHeight + diffScale; 
                    top = this.startX - (e.pageX - this.startMouseX);
                }else if (this.resizer.classList.contains('bottom-left')) {
                    let diffScale = (this.startWidth - (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth - (e.pageX - this.startMouseX)
                    height = this.startHeight + diffScale; 
                    left = this.startX + (e.pageX - this.startMouseX)
                }else if (this.resizer.classList.contains('middle-left')) {
                    width = this.startWidth - (e.pageX - this.startMouseX)
                    left = this.startX + (e.pageX - this.startMouseX)
                }else if (this.resizer.classList.contains('top-middle')) {
                    height = this.startHeight - (e.pageY - this.startMouseY); 
                    top = this.startY + (e.pageY - this.startMouseY);
                }else if (this.resizer.classList.contains('top-left')) {
                    let diffScale = (this.startWidth - (e.pageX - this.startMouseX)) - this.startWidth
                    width = this.startWidth - (e.pageX - this.startMouseX)
                    height = this.startHeight + diffScale; 
                    let diffPosition = (this.startX + (e.pageX - this.startMouseX)) - this.startX
                    left = this.startX + (e.pageX - this.startMouseX)
                    top = this.startY + diffPosition;
                }
                this.updateToken({
                    id: this.token.id,
                    width: width,
                    height: height,
                    left: left,
                    top: top,
                    zIndex: this.token.zIndex
                })
            },
            removeToken: function(e) {
                if (e.key === "Delete") {
                    this.isResizing = false;
                    this.isContexting = false;
                    document.removeEventListener('keydown', this.removeToken)
                    this.removeTokenOnMap({id: this.token.id, mercure: false})
                }
            },
            /**
             * 
             * @param {*} e 
             */
            clickOutside: function(e) {
                if(!this.$el.contains(e.target)){
                    window.removeEventListener('click', this.clickOutside)
                    this.isResizing = false;
                    this.isContexting = false;
                    document.removeEventListener('keydown', this.removeToken)
                }
            },
            setTokenWidth: function(e) {
                this.$store.commit('map/setTokenWidth', {
                    token: this.token,
                    width: e.target.value
                })
            },
            setTokenHeight: function(e) {
                this.$store.commit('map/setTokenHeight', {
                    token: this.token,
                    height: e.target.value
                })
            },
            setTokenTop: function(e) {
                this.$store.commit('map/setTokenTop', {
                    token: this.token,
                    top: e.target.value
                })
            },
            setTokenLeft: function(e) {
                this.$store.commit('map/setTokenLeft', {
                    token: this.token,
                    left: e.target.value
                })
            },
            setTokenZIndex: function(e) {
                this.$store.commit('map/setTokenZIndex', {
                    token: this.token,
                    zIndex: e.target.value
                })
            },
            addTokenPlayer: function(e) {
                if(e.target.checked) {
                    this.$store.commit('map/addTokenPlayer', {
                        token: this.token,
                        user: { id: e.target.value }
                    })
                }else {
                    this.$store.commit('map/removeTokenPlayer', {
                        token: this.token,
                        user: { id: this.getIndexOfUser({ id: e.target.value}, this.id) }
                    })
                }
            },
            submitForm: function() {
                let users = []
                this.token.users.forEach(user => {
                    users.push('api/users/' + user.id)
                });
                this.finishUpdateToken({
                    id: Number(this.token.id),
                    width: Number(this.token.width),
                    height: Number(this.token.height),
                    left: Number(this.token.left),
                    top: Number(this.token.top),
                    zIndex: Number(this.token.zIndex),
                    users: users
                })
                this.isPropertiesContexting = false
            }
        }
    }
</script>

<style scoped>
    .token {
        position: absolute;
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

    picture {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    img {
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
        color: #D68836;
        margin-bottom: 4px;
    }
</style>