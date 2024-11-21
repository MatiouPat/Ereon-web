<template>
    <div class="multi-step-form">
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
        <div ref="steps" class="step-body">
            <slot></slot>
        </div>
        <div class="footer">
            <button class="btn btn-secondary" v-html="currentStep > 0 ? 'Précédent' : returnMsg" v-on="{ click: currentStep > 0 ? previousStep : cancelForm }"></button>
            <button class="btn btn-primary" v-on="{ click: stepsNumber-1 > currentStep ? nextStep : validForm }">{{ stepsNumber-1 == currentStep ? finalMsg : 'Suivant' }}</button>
        </div>
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
        returnMsg: {
            type: String,
            default: 'Annuler'
        },
        finalMsg: {
            type: String,
            default: 'Valider'
        }
    },
    methods: {
        previousStep: function () {
            this.steps[this.currentStep].classList.add('undisplayed')
            this.steps[this.currentStep-1].classList.remove('undisplayed')
            this.currentStep--;
        },
        nextStep: function() {
            this.steps[this.currentStep].classList.add('undisplayed')
            this.steps[this.currentStep+1].classList.remove('undisplayed')
            this.currentStep++;
        },
        validForm: function() {
            this.$emit('form:valid');
        },
        cancelForm: function() {
            this.$emit('form:cancel');
        }
    },
    emits: ['form:valid', 'form:cancel'],
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

.multi-step-form {
    height: 100%;
}

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

.step::after {
    content: "";
    position: absolute;
    top: 20px;
    left: 0;
    width: 0;
    height: 4px;
    background: #D87D40;
    z-index: -1;
    transition: all .2s linear;
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
    transition: all .2s linear;
}

.step.completed::after {
    width: 100%;
    transition: all .2s linear;
}

.step.in-progress::after {
    width: 50%;
    transition: all .2s .2s linear;
}

:is(.step.completed, .step.in-progress) .step-mark-icon {
    background-color: #D87D40;
    transition: all .4s .2s linear;
}

.step-content {
    display: inline-block;
    width: 100%;
    text-align: center;
    margin-top: 4px;
}

.step-body {
    height: calc(100% - 97px);
}

.footer {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
}

.dark .step-mark-icon {
    background-color: #090D11;
}

.dark .step::before {
    background: #090D11;
}

</style>