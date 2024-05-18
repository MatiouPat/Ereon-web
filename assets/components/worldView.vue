<template>
    <div class="world-view-wrapper">
        <div class="world-view">
            <h1>Créer mon monde</h1>
            <multi-step-form :final-msg="'Créer le monde'" @form:valid="createWorld">
                <step :title="'Base'">
                    <div>
                        <basic-input :model-value="world.name" :label="'Nom'" @update:model-value="(modelValue) => world.name = modelValue"></basic-input>
                    </div>
                </step>
            </multi-step-form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { World } from '../entity/world';
import basicInput from './forms/inputs/basicInput.vue';
import MultiStepForm from './forms/multiples/multiStepForm.vue';
import Step from './forms/multiples/step.vue';
import { WorldService } from '../services/worldService';
import { mapState } from 'pinia';
import { useUserStore } from '../store/user';

export default defineComponent({
    data() {
        return {
            worldService: new WorldService as WorldService,
            world: {} as World
        }
    },
    computed: {
        ...mapState(useUserStore, [
            'getUserId'
        ])
    },
    methods: {
        createWorld: function() {
            this.worldService.createWorld(this.world, this.getUserId);
        }
    },
    components: { basicInput, MultiStepForm, Step }
})

</script>

<style scoped>
    .world-view-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        background-color: #FFFFFF;
        z-index: 11;
    }

    .world-view {
        width: 480px;
    }

    h1 {
        font-size: 3rem;
        line-height: 3rem;
        font-weight: 700;
        padding-bottom: 24px;
    }

    .dark .world-view-wrapper {
        background-color: #1F262D;
    }
</style>