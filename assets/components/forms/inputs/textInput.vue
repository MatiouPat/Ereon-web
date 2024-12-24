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
    name: "TextInput",
    inject: {
        step: {
            from: 'step',
            default: null
        }
    },
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
    emits: ['update:modelValue'],
    mounted() {
        if(this.step) {
            this.step.registerInput(this);
        }
    }
})
</script>

<style>

    .text-label {
        display: block;
        max-width: 100%;
        padding: 6px 0;
        transition: all .1s ease;
    }

    .ql-toolbar {
        color: #333333;
        background: #FFFFFF;
        border: solid 1px rgba(0, 0, 0, 0.3);
        border-style: inset;
        transition: all .05s ease;
    }

    .dark .ql-toolbar {
        /*background: #1c1b22;*/
        background: none;
        color: #b3b3b3;
    }

    .dark .form-group:has(.ql-editor:focus) .ql-toolbar {
        border: solid 1px #D87D40 !important;
        background: #1F262D;
    }

    .dark .form-group:has(.ql-editor:focus) .html-editor {
        border: solid 1px #D87D40 !important;
        border-top: none !important;
        background: #1F262D;
    }

    .dark .form-group:has(.ql-editor:focus) .text-label {
        color: #D87D40;
    }

    .dark .ql-fill {
        fill: #F3F4F4;
    }

    .dark .ql-stroke {
        stroke: #F3F4F4;
    }

    .html-editor {
        border: solid 1px rgba(0, 0, 0, 0.3);
        border-top: none;
        height: auto;
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