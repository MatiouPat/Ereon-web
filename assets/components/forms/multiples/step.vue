<template>
    <div :title="title">
        <slot></slot>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "Step",
    inject: ["multiStepForm"],
    provide() {
        return {
            step: this
        }
    },
    data() {
      return {
          inputs: [] as []
      }
    },
    props: {
        title: {
            type: String,
            default: ''
        }
    },
    methods: {
        registerInput(input): void
        {
            this.inputs.push(input);
        },
        validate(): boolean
        {
            let isValid = true;
            this.inputs.forEach((input) => {
                const result = input.validate();
                isValid = result && isValid;
            })
            return isValid;
        }
    },
    mounted() {
        if(!this.multiStepForm) {
            console.error(`The parent of a Step must be of type MultiStepForm`)
        }else {
            this.multiStepForm.registerStep(this)
        }
    }
})

</script>

<style scoped>

.undisplayed {
    display: none;
}

</style>