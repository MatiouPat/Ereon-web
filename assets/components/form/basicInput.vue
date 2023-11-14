<template>
    <div class="form-group" :class="label ? 'label' : null">  
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
    position: relative;
    width: 100%;
    height: 28px;
}

.form-group.label {
    height: 48px;
}

.form-group:hover {
    background: rgba(0, 0, 0, .04);
    box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .5)
}

label {
    position: absolute;
    top: 20px;
    left: 0;
    display: block;
    max-width: 100%;
    margin: 4px;
    transition: all .1s ease;
}

input {
    position: absolute;
    bottom: 0;
    left: 0;
    color: #333333;
    background: none;
    border: none;
    box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.3);
    width: 100%;
    padding: 0 4px;
    transition: all .15s ease;
    font-family: Oswald, sans-serif;
}

input:focus {
    outline: none;
    box-shadow: inset 0 -2px 0 rgb(214, 136, 54) !important;
}

.form-group:has(> input:focus) label {
    top: 0;
    color: #D68836;
}

.form-group:has(> input:not(:placeholder-shown)) label, .form-group:has(> input.value) label {
    top: 0;
}

.dark input {
    color: #F3F4F4;
    box-shadow: inset 0 -1px 0 #F3F4F4;
}

.dark .form-group:has(> input:focus) {
    background: #1c1b22;
}

</style>