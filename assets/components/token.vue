<template>
    <div class="token" ref="token" :style="{top: token.top + 'px', left: token.left + 'px', width: token.width + 'px', height: token.height + 'px'}" @mousedown="onMouseDown">
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
                startX: 0,
                startY: 0,
                startWidth: 0,
                startHeight: 0,
                startMouseX: 0,
                startMouseY: 0,
            }
        },
        props: [
            'id'
        ],
        computed: {
            ...mapGetters('map', [
                'map',
                'getTokenById'
            ]),
            token() {
                return this.getTokenById(this.id)
            },
        },
        methods: {
            ...mapActions('map', [
                'updateToken',
                'finishUpdateToken'
            ]),
            /**
             * 
             * @param {*} e 
             */
            onMouseDown: function (e) {
                e.preventDefault();
                if(e.button === 0) {
                    this.isResizing = true;
                    window.addEventListener('click', this.clickOutside)
                    this.startX = e.screenX - this.$refs.token.offsetLeft;
                    this.startY = e.screenY - this.$refs.token.offsetTop;
                    document.addEventListener('mousemove', this.onMove)
                    document.addEventListener('mouseup', () => {
                        this.finishUpdateToken({
                            id: this.token.id,
                            width: this.token.width,
                            height: this.token.height,
                            left: this.token.left,
                            top: this.token.top
                        })
                        document.removeEventListener('mousemove', this.onMove)
                    }, { once: true })
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
                    top: top
                })
            },
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
                        top: this.token.top
                    })
                    document.removeEventListener('mousemove', this.onResize)
                }, { once: true })
            },
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
                console.log(width, height, top, left)
                this.updateToken({
                    id: this.token.id,
                    width: width,
                    height: height,
                    left: left,
                    top: top
                })
            },
            clickOutside: function(e) {
                if(!this.$el.contains(e.target)){
                    this.isResizing = false;
                    window.removeEventListener('click', this.clickOutside)
                }
            }
        }
    }
</script>

<style scoped>
    .token {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 2;
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
</style>