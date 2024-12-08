<template>
    <div class="form-group" :class="hasError ? 'error' : ''">
        <label v-if="label">{{ label }}<i v-if="isRequired && mustShowRequiredLabel">*</i></label>
        <input v-if="isPassword" type="password" v-model="value" :autocomplete="autocomplete">
        <input v-else-if="!isNumber" v-model="value">
        <input v-else v-model.number="value">
        <span v-if="hasError" class="form-error">{{ messageEroor }}</span>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: "BasicInput",
    inject: ["step", "collection"],
    data() {
        return {
            hasError: false as boolean,
            messageEroor: "Ce champs est requis" as string
        }
    },
    props: {
        label: {
            type: String,
            default: ""
        },
        modelValue: {
            type: [String, Number]
        },
        isNumber: {
            type: Boolean,
            default: false
        },
        isPassword: {
            type: Boolean,
            default: false
        },
        autocomplete: {
            type: String,
            default: 'off'
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
    methods: {
        validate(): boolean
        {
            if (this.isRequired && !this.value) {
                this.hasError = true;
                return false;
            }else {
                this.hasError = false;
                return true;
            }
        }
    },
    emits: ['update:modelValue'],
    mounted() {
        if (this.$parent.$options.name == "TransitionGroup") {
            this.collection.registerInput(this)
        }else if(this.step) {
            this.step.registerInput(this)
        }
    }
})

</script>

<style scoped>

.form-group {
    width: 100%;
}

label {
    display: block;
    max-width: 100%;
    transition: all .1s ease;
}

input {
    color: #333333;
    background: none;
    border: solid 1px #090D11;
    width: 100%;
    padding: 2px 4px;
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

.form-group.error input {
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

.dark input {
    color: #F3F4F4;
    border: solid 1px #F3F4F4;
}

.dark input:focus {
    background: #1F262D;
}

i {
    color: #D87D40;
}

</style>