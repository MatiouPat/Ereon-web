<template>
    <div class="form-group">  
        <label v-if="label">{{ label }}</label>
        <input v-if="!isNumber" v-model="value" :class="value ? 'value' : null" placeholder="&nbsp;">
        <input v-else v-model.number="value">
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    props: {
        label: {
            type: String,
            default: ""
        },
        modelValue: {
            type: [String, Number],
            default: ""
        },
        isNumber: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        value: {
            get() {
                return this.modelValue
            },
            set(value: string | number) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    emits: ['update:modelValue']
})

</script>

<style scoped>

.form-group {
    width: 100%;
}

label {
    display: block;
    max-width: 100%;
    padding: 6px 0;
    transition: all .1s ease;
}

input {
    color: #333333;
    background: none;
    border: solid 1px #090D11;
    width: 100%;
    padding: 0 4px;
    transition: all .15s ease;
    font-family: Oswald, sans-serif;
}

input:focus {
    outline: none;
    border: solid 1px #D87D40 !important;
}

input:hover {
    background: rgba(0, 0, 0, .04);
}

.form-group:has(> input:focus) label {
    color: #D87D40;
}

.dark input {
    color: #F3F4F4;
    border: solid 1px #F3F4F4;
}

.dark input:focus {
    background: #1F262D;
}

</style>