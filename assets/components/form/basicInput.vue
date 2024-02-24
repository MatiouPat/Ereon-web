<template>
    <div class="form-group" :class="hasError ? 'error' : ''">  
        <label v-if="label">{{ label }}<i v-if="isRequired">*</i></label>
        <div v-if="hasError" class="form-error"><span class="form-error-prefix">ERREUR</span><span>{{ messageEroor }}</span></div>
        <input v-if="isPassword" type="password" v-model="value" :autocomplete="autocomplete">
        <input v-else-if="!isNumber" v-model="value">
        <input v-else v-model.number="value">
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    data() {
        return {
            hasError: false as boolean,
            messageEroor: "" as string
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
        setError: function(message: string) {
            this.messageEroor = message;
            this.hasError = true;
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

.form-group.error input {
    border-color: #CB2D2A;
}

.form-error {
    display: block;
    margin-bottom: 10px;
}

.form-error-prefix {
    color: #F3F4F4;
    background-color: #CB2D2A;
    border-radius: 4px;
    padding: 1px 4px 2px 4px;
    margin-right: 4px;
    font-size: 12px;
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