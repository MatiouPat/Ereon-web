<template>
    <div class="editor-wrapper" @mousedown="onMouseDown" @mouseup="onMouseUp" @wheel="onWheel" @contextmenu="onContextMenu" ref="editor">
        <div class="editor" ref="map" :style="{ width: width * ratio + 'px', height: height * ratio + 'px' }">
            <picture>
                <source type="image/webp" srcset="build/images/test2.webp">
                <img class="map" src="build/images/test.png" alt="Map">
            </picture>
            <token></token>
        </div>
    </div>
</template>

<script>
    import Token from './token.vue'

    export default {
        components: {
            Token
        },
        data() {
            return {
                width: 1200,
                height: 1200,
                ratio: 1,
                startX: 0,
                startY: 0,
                mapX: 0,
                mapY: 0 
            }
        },
        methods: {
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
                    } else if (this.ratio - e.deltaY * 0.00045 >= 2.5) {
                        this.ratio = 2.5;
                    } else if (this.ratio >= 0.1 && this.ratio <= 2.5) {
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
        }
    }
</script>

<style>
    .editor-wrapper {
        overflow: scroll;
        height: 100%;
        width: calc(100dvw - 145px);
        background-color: #E2E2E2;
    }

    .editor {
        position: relative;
        display: inline-block;
        margin: 200px;
        width: 2000px;
    }

    .editor picture {
        display: block;
        width: 100%;
    }

    .map {
        display: block;
        width: 100%;
    }
</style>