<template>
    <div class="editor-wrapper" ref="editor">
        <div class="editor" ref="map" :style="{ width: width * ratio + 'px', height: height * ratio + 'px' }">
            <picture>
                <source type="image/webp" srcset="build/images/test2.webp">
                <img src="build/images/test.png" alt="Map">
            </picture>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                width: 1200,
                height: 1200,
                ratio: 1
            }
        },
        methods: {
            /**
             * 
             * @param {HTMLElement} element 
             * @param {Function} cb 
             */
            scrollMap: function (element, cb) {
                element.addEventListener('mousedown', onMouseDown);
                let startX, startY;
                let mapX, mapY;

                /**
                 * 
                 * @param {PointerEvent} e 
                 */
                function onMouseDown(e)
                {
                    e.preventDefault();
                    if(e.button === 2) {
                        startX = e.screenX - element.offsetLeft;
                        startY = e.screenY - element.offsetTop;
                        mapX = element.scrollLeft;
                        mapY = element.scrollTop;
                        document.addEventListener('mousemove', onMouseMove);
                        document.addEventListener('mouseup', onMouseUp, { once: true });
                    }
                }

                /**
                 * 
                 * @param {PointerEvent} e 
                 */
                function onMouseUp(e)
                {
                    document.removeEventListener('mousemove', onMouseMove);
                }

                /**
                 * 
                 * @param {PointerEvent} e 
                 */
                function onMouseMove(e)
                {
                    cb([mapX - (e.screenX - element.offsetLeft - startX), mapY - (e.screenY - element.offsetTop - startY)]);
                }
            },
            zoomMap: function (element) {
                element.addEventListener('wheel', (e) => {
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
                });
            },
            rightclick: function (element) {
                element.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                })
            }
        },
        mounted: function () {
            let editor = this.$refs.editor
            this.scrollMap(editor, function (coordinate) {
                editor.scrollLeft = coordinate[0]
                editor.scrollTop = coordinate[1]
            })
            this.zoomMap(editor)
            this.rightclick(editor)
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

    .editor img {
        display: block;
        width: 100%;
    }
</style>