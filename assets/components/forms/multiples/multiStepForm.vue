<template>
    <nav>
        <ul class="steps">
            <li class="step" :class="{'completed' : key < currentStep, 'in-progress' : key == currentStep}" :key="key" v-for="(step, key) in steps">
                <div class="step-mark">
                    <div class="step-mark-icon">{{ key+1 }}</div>
                </div>
                <span class="step-content">{{ step.title }}</span>
            </li>
        </ul>
    </nav>
    <div ref="steps">
        <slot></slot>
    </div>
    <div class="footer">
        <button class="btn btn-primary" v-on="{ click: stepsNumber-1 > currentStep ? nextStep : validForm}">{{ stepsNumber-1 == currentStep ? finalMsg : 'Suivant' }}</button>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    data(){
        return {
            currentStep: 0 as number,
            stepsNumber: 0 as number,
            steps: {} as any
        }
    },
    props: {
        finalMsg: {
            type: String,
            default: ''
        }
    },
    methods: {
        nextStep: function() {
            this.steps[this.currentStep].classList.add('undisplayed')
            this.steps[this.currentStep+1].classList.remove('undisplayed')
            this.currentStep++;
        },
        validForm: function() {
            this.$emit('form:valid');
        }
    },
    emits: ['form:valid'],
    mounted(){
        this.steps = this.$refs.steps.children;
        this.stepsNumber = this.steps.length
        let index = 0;
        for(let step of this.steps) {
            if(index > 0) {
                step.classList.add('undisplayed');
            }
            index++;
        }
    }
})

</script>

<style scoped>

.steps {
    display: flex;
    justify-content: space-between;
}

.step {
    position: relative;
    width: 100%;
}

.step::before {
    content: "";
    position: absolute;
    top: 20px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #090D11;
	z-index: -1;
}

.step-mark-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 400;
    font-size: 1.2rem;
    width: 40px;
    height: 40px;
    margin-left: calc(50% - 20px);
    margin-top: 0;
    background-color: #1F262D;
    border-radius: 16px;
}

.step.completed::after {
    content: "";
    position: absolute;
    top: 20px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #D87D40;
	z-index: -1;
}

.step.in-progress::after {
    content: "";
    position: absolute;
    top: 20px;
    left: 0;
    width: 50%;
    height: 4px;
    background: #D87D40;
	z-index: -1;
}

:is(.step.completed, .step.in-progress) .step-mark-icon {
    background-color: #D87D40;
}

.step-content {
    display: inline-block;
    width: 100%;
    text-align: center;
    margin-top: 4px;
}

.footer {
    display: flex;
    justify-content: flex-end;
}

.dark .step-mark-icon {
    background-color: #090D11;
}

.dark .step::before {
    background: #090D11;
}

</style>