<template>
    <div class="file-wrapper" :style="{width: width + 'px', height: height + 'px', minWidth: width + 'px'}">
        <div v-if="image.imageName || isPreview">
            <picture class="file-image">
                <img :src="'/uploads/images/' + image.imageName" :alt="image.imageName" ref="image">
            </picture>
            <img width="24" height="24" src="/build/images/icons/delete_white.svg" alt="Supprimer l'image" class="file-delete-icon" @click="deleteImage">
        </div>
        <div class="file-input" v-else>
            <label for="imageFile">Charger une image</label>
            <input type="file" id="imageFile" @change="uploadImage">
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { Image } from '../../../entity/image';

export default defineComponent({
    data() {
        return {
            isPreview: false as boolean
        }
    },
    props: {
        modelImage: {
            type: Object as () => Image,
            default: {} as Image
        },
        width: {
            type: Number,
            default: 80
        },
        height: {
            type: Number,
            default: 80
        }
    },
    computed: {
        image: {
            get() {
                return this.modelImage;
            },
            set(image: Image) {
                this.$emit('update:modelImage', image)
            }
        }
    },
    methods: {
        deleteImage: function() {
            this.isPreview = false
            this.image = {};
        },
        uploadImage: function(e: Event) {
            this.image.imageFile = (e.target as HTMLInputElement).files![0];
            this.image.imageName = "";
            this.$emit('update:modelImage', this.image)
            let reader = new FileReader();
            this.isPreview = true;
            reader.onload = (e) => {
                let result = e.target?.result;
                if(typeof result === "string") {
                    (this.$refs.image as HTMLImageElement).src = result;
                }
            }
            reader.readAsDataURL(this.image.imageFile);
        }
    },
    emits: ['update:modelImage']
})
</script>

<style scoped>
    .file-wrapper {
        position: relative;
        display: block;
        background: #BBBFC3;
    }

    .file-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .file-image  img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .file-delete-icon {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #dc3545 ;
        border-radius: 4px;
        padding: 2px;

    }

    .file-input {
        width: 100%;
        height: 100%;
    }

    .file-input label {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .file-input input[type=file] {
        display: none;
    }

    .dark .file-wrapper {
        background: #1B2229;
    }
</style>