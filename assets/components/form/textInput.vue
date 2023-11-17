<template>
    <div class="form-group">
        <label class="text-label" v-if="label">{{ label }}</label>
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
        label: {
            type: String,
            default: ""
        },
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

    .form-group.label .ql-toolbar {
        top: 20px;
    }

    .form-group:hover {
        background: rgba(0, 0, 0, .04);
    }

    .text-label {
        display: block;
        max-width: 100%;
        padding: 4px;
        transition: all .1s ease;
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
        box-shadow: inset 0 -1px 0 #F3F4F4;
    }

    .dark .form-group:has(.ql-editor:focus) .ql-toolbar {
        box-shadow: inset 0 -2px 0 rgb(214, 136, 54) !important;
    }

    .dark .form-group:has(.ql-editor:focus) {
        background: #1c1b22;
    }

    .dark .ql-fill {
        fill: #F3F4F4;
    }

    .dark .ql-stroke {
        stroke: #F3F4F4;
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
        color: #F3F4F4;
    }

    .dark .ql-editor:focus {
        background: #1F262D;
    }

</style>