<template>
    <div class="token" ref="token" :style="{top: top + 'px', left: left + 'px', width: width + 'px', height: height + 'px'}" @mousedown.stop="onMouseDown">
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
        <picture class="token-content">
            <source type="image/webp" srcset="build/images/token.webp">
            <img :style="{width: width + 'px', height: height + 'px'}" src="build/images/token.png" alt="Token">
        </picture>
    </div>
    
</template>

<script>
    export default {
        data() {
            return {
                top: 0,
                left: 0,
                width: 100,
                height: 100,
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
        methods: {
            /**
             * 
             * @param {*} e 
             */
            onMouseDown: function (e) {
                e.preventDefault();
                this.isResizing = true;
                window.addEventListener('click', this.clickOutside)
                if(e.button === 0) {
                    this.startX = e.screenX - this.$refs.token.offsetLeft;
                    this.startY = e.screenY - this.$refs.token.offsetTop;
                    document.addEventListener('mousemove', this.onMouseMove)
                    document.addEventListener('mouseup', this.onMouseUp)
                }
            },
            /**
             * 
             * @param {*} e 
             */
            onMouseMove: function(e) {
                e.preventDefault();
                if (e.screenX - this.startX > -this.width/2 && e.screenX - this.startX < this.$parent.width - this.width/2) {
                    this.left += e.screenX - this.$refs.token.offsetLeft - this.startX
                }
                if (e.screenY - this.startY > -this.height/2 && e.screenY - this.startY < this.$parent.height - this.height/2) {
                    this.top += e.screenY - this.$refs.token.offsetTop - this.startY
                }
            },
            /**
             * 
             */
            onMouseUp: function() {
                document.removeEventListener('mousemove', this.onMouseMove)
            },
            resize: function(e) {
                e.preventDefault()
                this.resizer = e.target
                this.startWidth = this.width;
                this.startHeight = this.height;
                this.startX = this.left;
                this.startY = this.top;
                this.startMouseX = e.pageX;
                this.startMouseY = e.pageY;
                document.addEventListener('mousemove', this.onResize)
                document.addEventListener('mouseup', () => {
                    document.removeEventListener('mousemove', this.onResize)
                })
            },
            onResize: function(e) {
                if (this.resizer.classList.contains('bottom-right')) {
                    let diff = (this.startWidth + (e.pageX - this.startMouseX)) - this.width
                    this.width =  this.startWidth + (e.pageX - this.startMouseX)
                    this.height += diff 
                }else if (this.resizer.classList.contains('middle-right')) {
                    this.width =  this.startWidth + (e.pageX - this.startMouseX)
                }else if (this.resizer.classList.contains('bottom-middle')) {
                    this.height = this.startHeight + (e.pageY - this.startMouseY)
                }else if (this.resizer.classList.contains('top-right')) {
                    let diffScale = (this.startWidth + (e.pageX - this.startMouseX)) - this.width
                    this.width = this.startWidth + (e.pageX - this.startMouseX)
                    this.height += diffScale; 
                    this.top = this.startX - (e.pageX - this.startMouseX);
                }else if (this.resizer.classList.contains('bottom-left')) {
                    let diffScale = (this.startWidth - (e.pageX - this.startMouseX)) - this.width
                    this.width = this.startWidth - (e.pageX - this.startMouseX)
                    this.height += diffScale; 
                    this.left = this.startX + (e.pageX - this.startMouseX)
                }else if (this.resizer.classList.contains('middle-left')) {
                    this.width = this.startWidth - (e.pageX - this.startMouseX)
                    this.left = this.startX + (e.pageX - this.startMouseX)
                }else if (this.resizer.classList.contains('top-middle')) {
                    this.height = this.startHeight - (e.pageY - this.startMouseY); 
                    this.top = this.startY + (e.pageY - this.startMouseY);
                }else if (this.resizer.classList.contains('top-left')) {
                    let diffScale = (this.startWidth - (e.pageX - this.startMouseX)) - this.width
                    this.width = this.startWidth - (e.pageX - this.startMouseX)
                    this.height += diffScale; 
                    let diffPosition = (this.startX + (e.pageX - this.startMouseX)) - this.left
                    this.left = this.startX + (e.pageX - this.startMouseX)
                    this.top += diffPosition;
                }
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

<style>
    .token {
        position: absolute;
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

    .token-content {
        position: absolute;
        top: 0;
        left: 0;
    }
</style>