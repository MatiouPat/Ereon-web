<template>
    <div class="form-group">
        <quill-editor class="html-editor" v-model:content="value" contentType="html" :options="options"/>
    </div>
</template>

<script lang="ts">
import { QuillEditor } from '@vueup/vue-quill'
import { defineComponent } from 'vue'

export default defineComponent({
    data() {
        return {
            options: {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }],
                    ]
                },
                theme: 'snow'
            }
        }
    },
    props: {
        modelValue: {
            type: String,
            default: ""
        }
    },
    computed: {
        value: {
            get() {
                return this.modelValue
            },
            set(value: string) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    components: {
        QuillEditor
    },
    emits: ['update:modelValue']
})
</script>

<style>

    .form-group {
        height: 100%;
    }

    .form-group:hover {
        background: rgba(0, 0, 0, .04);
    }

    .ql-toolbar {
        color: #333333;
        background: #FFFFFF;
        border: none !important;
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.3);
        border-style: inset;
        transition: all .05s ease;
    }

    .dark .ql-toolbar {
        /*background: #1c1b22;*/
        background: none;
        color: #b3b3b3;
        box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.3);
    }

    .dark .form-group:has(.ql-editor:focus) .ql-toolbar {
        background: #1c1b22;
        box-shadow: inset 0 -2px 0 rgb(214, 136, 54) !important;
    }

    .dark .ql-fill {
        fill: #b3b3b3;
    }

    .dark .ql-stroke {
        stroke: #b3b3b3;
    }

    .html-editor {
        border: none !important;
        height: 100%;
        transition: all .15s ease;
    }

    .ql-editor {
        color: #333333;
        background: #FFFFFF;
    }

    .dark .ql-editor {
        /*background: #1c1b22;*/
        background: none;
        color: #b3b3b3;
    }

    .dark .ql-editor:focus {
        background: #1c1b22;
    }

</style>