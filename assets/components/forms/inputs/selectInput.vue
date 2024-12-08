<template>
    <div class="form-group" :class="hasError ? 'error' : ''">
        <label v-if="label">{{ label }}</label>
        <select v-if="typeof modelValue == 'string'" v-model="value" @change="$emit('update:modelValue', value)" :style="{background: background}">
            <option v-if="hasDefault" value="-1" selected disabled hidden>Selectioner une valeur</option>
            <option :key="key" v-for="(choice, key) in choices" :value="choice.id">{{ choice.value }}</option>
        </select>
        <select v-if="typeof modelValue == 'number'" v-model.number="value" @change="$emit('update:modelValue', value)" :style="{background: background}">
            <option v-if="hasDefault" value="-1" selected disabled hidden>Selectioner une valeur</option>
            <option :key="key" v-for="(choice, key) in choices" :value="choice.id">{{ choice.value }}</option>
        </select>
        <span v-if="hasError" class="form-error">{{ messageEroor }}</span>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    name: "SelectInput",
    inject: ["step", "collection"],
    data() {
        return {
            value: this.modelValue,
            hasError: false as boolean,
            messageEroor: "Ce champs est requis" as string
        }
    },
    props: {
        label: {
            type: String,
            default: ""
        },
        choices: {
            type: Object,
            default: []
        },
        modelValue: {
            type: Number,
            default: -1
        },
        background: {
            type: String,
            default: '#090D11'
        },
        hasDefault: {
            type: Boolean,
            default: true
        },
        isRequired: {
            type: Boolean,
            default: false
        },
        mustShowRequiredLabel: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        validate(): boolean {
            if (this.isRequired && this.value == -1) {
                this.hasError = true;
                return false;
            } else {
                this.hasError = false;
                return true;
            }
        }
    },
    emits: ['update:modelValue'],
    mounted() {
        if (this.$parent.$options.name == "TransitionGroup") {
            this.collection.registerInput(this)
        }else if(this.step){
            this.step.registerInput(this)
        }
    }
})
</script>

<style scoped>
.form-group {
    position: relative;
    width: 100%;
}

.form-group::after {
    content: "";
    position: absolute;
    top: 34px;
    right: 8px;
    display: block;
    width: 0.8em;
    height: 0.5em;
    background-color: #090D11;
    -webkit-clip-path: polygon(100% 0%, 0 0%, 50% 100%);
    clip-path: polygon(100% 0%, 0 0%, 50% 100%);
}

.dark .form-group::after {
    background-color: #F3F4F4;
}

label {
    display: block;
    max-width: 100%;
    transition: all .1s ease;
}


select {
    display: block;
    background: none;
    border: none;
    border: solid 1px #090D11;
    width: 100%;
    font-family: Oswald, sans-serif;
    -moz-appearance:none; /* Firefox */
    -webkit-appearance:none; /* Safari and Chrome */
    appearance:none;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23131313%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
    background-repeat: no-repeat;
    background-position: right 0.7rem top 50%;
    background-size: 0.65rem auto;
    padding: 2px 4px;
}

.dark select {
    color: #F3F4F4;
    border: solid 1px #F3F4F4;
}

.form-group.error select {
    border-color: #CB2D2A;
}

.form-error {
    display: block;
    margin-top: 4px;
    color: #CB2D2A;
}

.form-error span {
    display: inline-block;
}

</style>