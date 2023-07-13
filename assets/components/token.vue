<template>
    <picture ref="token" @mousedown="onMouseDown" @mouseup="onMouseUp" :style="{top: top + 'px', left: left + 'px'}">
        <source type="image/webp" srcset="build/images/token.webp">
        <img width="100" height="100" src="build/images/token.png" alt="Token">
    </picture>
</template>

<script>
    export default {
        data() {
            return {
                top: 0,
                left: 0,
                startX: 0,
                startY: 0
            }
        },
        methods: {
            /**
             * 
             * @param {*} e 
             */
            onMouseDown: function (e) {
                e.preventDefault();
                if(e.button === 0) {
                    this.startX = e.screenX - this.$refs.token.offsetLeft;
                    this.startY = e.screenY - this.$refs.token.offsetTop;
                    document.addEventListener('mousemove', this.onMouseMove)
                }
            },
            /**
             * 
             * @param {*} e 
             */
            onMouseMove: function(e) {
                e.preventDefault();
                this.left += e.screenX - this.$refs.token.offsetLeft - this.startX
                this.top += e.screenY - this.$refs.token.offsetTop - this.startY
            },
            /**
             * 
             */
            onMouseUp: function() {
                document.removeEventListener('mousemove', this.onMouseMove)
            },
        }
    }
</script>

<style>
    picture {
        position: absolute;
        display: block;
        width: 100%;
        z-index: 2;
    }

    img {
        display: block;
    }
</style>