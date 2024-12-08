<template>
    <div class="world-view-wrapper">
        <div class="world-view">
            <h1>Créer mon monde</h1>
            <multi-step-form :return-msg="'Retour au menu principal'" :final-msg="'Créer le monde'" @form:valid="createWorld" @form:cancel="cancelWorldCreation">
                <step :title="'Base'">
                    <div>
                        <basic-input
                            :label="'Nom'"
                            :model-value="world.name"
                            :is-required="true"
                            @update:model-value="(modelValue) => world.name = modelValue"
                        ></basic-input>
                        <image-input
                            :label="'Image'"
                            :modelImage="world.image"
                            :width="192"
                            :height="192"
                            @update:modelImage="(modelImage) => world.image = modelImage"
                        ></image-input>
                    </div>
                </step>
                <step :title="'Caractéristiques'">
                    <collection-input class="attributes-group" :model-value="world.attributes" @add:model-value="addAttribute" @remove:model-value="(attributeIndex) => removeAttribute(attributeIndex)">
                        <template v-slot:default="{item, index}">
                            <basic-input
                                :label="'Nom'"
                                :model-value="item.name"
                                :is-required="true"
                                :must-show-required-label="false"
                                @update:model-value="(newValue) => item.name = newValue"
                            ></basic-input>
                            <basic-input
                                :label="'Acronyme'"
                                :model-value="item.acronym"
                                :is-required="true"
                                :must-show-required-label="false"
                                @update:model-value="(newValue) => item.acronym = newValue"
                            ></basic-input>
                        </template>
                    </collection-input>
                </step>
                <step :title="'Compétences'">
                    <collection-input class="attributes-group" :model-value="world.skills" @add:model-value="addSkill" @remove:model-value="(skillIndex) => removeSkill(skillIndex)">
                        <template v-slot:default="{item, index}">
                            <basic-input
                                :label="'Nom'"
                                :model-value="item.name"
                                :is-required="true"
                                :must-show-required-label="false"
                                @update:model-value="(newValue) => item.name = newValue"
                            ></basic-input>
                            <select-input
                                :label="'Attribut lié'"
                                :model-value="item.attribute.acronym"
                                :choices="attributeChoices"
                                :background="getIsDarkTheme ? '#1f262d' : '#FFFFFF'"
                                :is-required="true"
                                :must-show-required-label="false"
                                @update:model-value="(modelValue) => {item.attribute.acronym = modelValue}"
                            ></select-input>
                        </template>
                    </collection-input>
                </step>
            </multi-step-form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { World } from '../entity/world';
import BasicInput from './forms/inputs/basicInput.vue';
import MultiStepForm from './forms/multiples/multiStepForm.vue';
import Step from './forms/multiples/step.vue';
import { WorldService } from '../services/worldService';
import { mapState } from 'pinia';
import { useUserStore } from '../store/user';
import CollectionInput from "./forms/inputs/collectionInput.vue";
import SelectInput from "./forms/inputs/selectInput.vue";
import ImageInput from "./forms/inputs/imageInput.vue";

export default defineComponent({
    data() {
        return {
            worldService: new WorldService as WorldService,
            world: {} as World
        }
    },
    computed: {
        ...mapState(useUserStore, [
            'getUserId',
            'getIsDarkTheme'
        ]),
        attributeChoices: function () {
            let attributeChoices = [];
            let index = 0;
            for(index; index < this.world.attributes.length-1; index++) {
                let acronym = this.world.attributes[index].acronym;
                attributeChoices.push({
                    id: index+1,
                    value: acronym
                });
            }
            return attributeChoices;
        }
    },
    methods: {
        createWorld: function() {
            this.worldService.createWorld(this.world, this.attributeChoices).then(world => {
                this.$emit('worldView:createdWorld', world);
            })
        },
        addAttribute: function() {
            this.world.attributes.push({ id: Date.now()})
        },
        removeAttribute: function(attributeIndex: number) {
            this.world.attributes.splice(attributeIndex, 1);
        },
        addSkill: function() {
            this.world.skills.push({id: Date.now(), attribute: {}})
        },
        removeSkill: function(skillIndex: number) {
            this.world.skills.splice(skillIndex, 1);
        },
        cancelWorldCreation: function() {
            this.$emit('worldView:cancel');
        }
    },
    components: {
        ImageInput,
        CollectionInput,
        BasicInput,
        MultiStepForm,
        Step,
        SelectInput
    },
    emits: ['worldView:cancel', 'worldView:createdWorld'],
    mounted() {
        this.world.attributes = []
        this.world.skills = []
    },
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
        width: 80%;
        max-width: 1600px;
        height: 80vh;
        display: flex;
        flex-direction: column;
    }

    h1 {
        font-size: 3rem;
        line-height: 3rem;
        font-weight: 700;
        padding-bottom: 24px;
    }

    .attributes-group ::v-deep(.form-collection) {
        display: grid;
        grid-template-columns: 1fr;
        column-gap: 16px;
    }

    .dark .world-view-wrapper {
        background-color: #1F262D;
    }

    ::v-deep(.multi-step-form) {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    ::v-deep(.multi-step-form nav) {
        width: 100%;
    }

    ::v-deep(.multi-step-form .step-body) {
        overflow: scroll;
        padding-top: 32px;
        padding-bottom: 8px;
        width: 920px;
    }

    ::v-deep(.multi-step-form .footer) {
        align-self: flex-end;
    }
</style>