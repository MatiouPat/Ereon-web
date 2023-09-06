<template>
    <div class="personages-box">
        <div class="personage" :key="personage.id" v-for="personage in personages" @click="viewPersonage(personage)">
            {{ personage.name }}
        </div>
        <Teleport to="#content">
            <div class="parameters-wrapper" v-show="isDisplayed">
                <div class="parameters">
                    <div class="parameters-header">
                            <h2>Personage</h2>
                            <img width="24" height="24" @click="isDisplayed = false" src="build/images/icons/close.svg" alt="Fermer">
                    </div>   
                    <div class="parameters-body">
                        <div class="personage-parameters">
                            <div class="personage-form">
                                <div class="form-group">
                                    <label class="form-label">Nom</label>
                                    <input class="form-control" type="text" v-model="currentPersonage.name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Race</label>
                                    <input class="form-control" type="text" v-model="currentPersonage.race">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alignement</label>
                                    <input class="form-control" type="text" v-model="currentPersonage.alignment">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Classe</label>
                                    <input class="form-control" type="text" v-model="currentPersonage.class">
                                </div>
                                <div class="form-group form-attributes">
                                    <div :key="key" v-for="(numberOfAttribute, key) in currentPersonage.numberOfAttributes">
                                        <label class="form-label">{{ numberOfAttribute.attribute?.name }}</label>
                                        <input class="form-control" type="number" v-model="numberOfAttribute.value">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Inventaire</label>
                                    <quill-editor class="html-editor" ref="inventory" :options="options" @text-change="inventoryChange"/>
                                </div>
                            </div>
                            <div class="parameters-footer">
                                <button class="btn" type="button" @click="cancel">Annuler</button>
                                <button class="btn" type="button" @click="updatePersonage">Valider</button>
                            </div>
                        </div>
                        <div class="personage-view">
                            <div class="personage-view-content" style="background-image: url('build/images/sheets/fond_fiche_bleu_2.webp');">
                                <div class="personage-view-block" style="background-image: url('build/images/sheets/texture_old_paper.png');">
                                    <span class="personage-name">{{ currentPersonage.name }}</span><br>
                                    <span class="personage-main-info">{{ currentPersonage.class }} {{ currentPersonage.race }}, {{ currentPersonage.alignment }}</span>
                                    <hr>
                                    <div class="personage-attributes">
                                        <div class="personage-attribute" :key="key" v-for="(numberOfAttribute, key) in currentPersonage.numberOfAttributes">
                                            <span class="personage-attribute-name">{{ numberOfAttribute.attribute?.acronym }}</span>
                                            <span class="personage-attribute-value">{{ numberOfAttribute.value }}</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div v-if="currentPersonage.inventory && currentPersonage.inventory !== '<p><br></p>' ">
                                        <h3>Inventaire</h3>
                                        <span v-html="currentPersonage.inventory"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Personage } from '../entity/personage';
import { PersonageRepository } from '../repository/personageRepository';
import { Quill, QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

export default defineComponent({
    data() {
        return {
            personageRepository: new PersonageRepository() as PersonageRepository,
            personages: [] as Personage[],
            currentPersonage: {} as Personage,
            isDisplayed: false as boolean,
            options: {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }],
                    ]
                },
                theme: 'snow'
            }
        }
    },
    components: {
        QuillEditor
    },
    methods: {
        viewPersonage: function(personage: Personage) {
            this.currentPersonage = personage;
            this.isDisplayed = true;
            (this.$refs.inventory as Quill).setHTML(this.currentPersonage.inventory);
        },
        inventoryChange: function() {
            this.currentPersonage.inventory = (this.$refs.inventory as Quill).getHTML()
        },
        cancel: function() {
            this.isDisplayed = false;
        },
        updatePersonage: function() {
            this.isDisplayed = false; 
            this.personageRepository.updatePersonagePartially(this.currentPersonage);
        }
    },
    mounted() {
        this.personageRepository.findAllPersonages().then(res => {
            this.personages = res;
        })
    }
})

</script>

<style scoped>

    .personages-box {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        gap: 8px;
    }

    .personage {
        border: solid 1px #565656;
        padding: 8px;
    }

    .parameters-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 5;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100dvw;
        height: 100dvh;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .parameters {
        display: block;
        min-width: 256px;
        width: 92%;
        min-height: 256px;
        height: 92%;
        background-color: #FFFFFF;
    }

    .parameters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border-bottom: solid 1px #565656;
        height: 40px;
    }

    .parameters-body {
        height: calc(100% - 40px);
        width: 100%;
        display: flex;
    }

    .personage-parameters {
        width: calc(100% - 1080px);
    }

    .personage-form {
        height: calc(100% - 48px);
        overflow-y: scroll;
        padding: 16px;
    }

    .parameters-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 48px;
        gap: 8px;
        padding: 8px;
        border-top: solid 1px #565656;
    }

    .personage-view {
        display: block;
        width: 1080px;
        height: 100%;
        overflow-y: scroll;
        background-color: #FFFFFF;
        color: #423d2a;
        line-height: 20px;
    }

    .personage-view-content {
        display: block;
        width: 1080px;
        height: 1396px;
        background-repeat: no-repeat;
        background-size: cover;
        padding: 48px;
    }

    .personage-view-block {
        position: relative;
        display: block;
        min-width: 256px;
        max-width: 400px;
        min-height: 800px;
        padding: 16px;
    }

    .personage-view-block::before {
        position: absolute;
        top: 0;
        left: 0;
        height: 4px;
        width: 100%;
        content: "";
        background-image: url('../images/sheets/background.png');
        border: solid 1px #584a30;
    }

    .personage-view-block::after {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
        content: "";
        background-image: url('../images/sheets/background.png');
        border: solid 1px #584a30;
    }

    .personage-name {
        display: inline-block;
        font-weight: 700;
        font-size: 2rem;
        color: #8C2417;
        padding-bottom: 8px;
    }

    .personage-main-info {
        font-style: italic;
        padding-bottom: 8px;
    }

    .personage-attributes {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        gap: 8px;
        width: 100%;
        font-size: 1.25rem;
        padding: 8px 0;
    }

    .personage-attribute {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .personage-attribute-name {
        font-weight: 500;
        color: #8C2417;
    }

    .personage-attribute-value {
        font-weight: 300;
    }

    hr {
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        color: #8C2417;
        background-color: #8C2417;
        height: 4px;
    }

    h3 {
        color: #8C2417;
        font-size: 1.5rem;
        padding-bottom: 4px;
        margin-bottom: 8px;
        border-bottom: solid 1px #8C2417;
    }

    .form-attributes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(156px, 1fr));
        column-gap: 8px;
        row-gap: 12px;
    }

    .dark .parameters-header {
        background-color: #1c1b22;
    }

    .dark .parameters-body {
        background-color: #2b2a33;
    }


</style>