<template>
    <div class="personages-box">
        <div class="personages">
            <div class="personage" :key="key" v-for="(personage, key) in personages" @click="viewPersonage(personage, key)">
                <img v-if="personage.image" :src="'/uploads/images/' + personage.image.imageName" alt="" width="64">
                <div class="personage-info">
                    <span class="personage-name">{{ personage.name }}</span>
                    <span class="personage-category me" v-if="personage.user && personage.user.id === getUserId">MOI</span>
                    <span class="personage-category pj" v-else-if="personage.user">PJ</span>
                    <span class="personage-category pnj" v-else>PNJ</span>
                </div>
            </div>
        </div>
        <button class="btn btn-primary" type="button" @click="createPersonage" v-if="isGameMaster">Ajouter un personnage</button>
        <Teleport to="#content">
            <div class="parameters-wrapper" v-if="isDisplayed">
                <div class="parameters">
                    <div class="parameters-header">
                            <h2>Informations personage</h2>
                            <img width="24" height="24" @click="isDisplayed = false" src="build/images/icons/close.svg" alt="Fermer">
                    </div>   
                    <div class="parameters-body">
                        <personage-sheet :currentPersonage="currentPersonage" @update:personage="(personage) => currentPersonage = personage"></personage-sheet>
                    </div>
                    <div class="parameters-footer">
                        <button class="btn btn-secondary" type="button" @click="cancel">Annuler</button>
                        <button class="btn btn-secondary" type="button" @click="deletePersonage" v-if="isGameMaster">Supprimer le personnage</button>
                        <button class="btn btn-primary" type="button" @click="updatePersonage" v-if="isModification">Modifier le personnage</button>
                        <button class="btn btn-primary" type="button" @click="updatePersonage" v-else>Créer le personnage</button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Personage } from '../../entity/personage';
import { PersonageService } from '../../services/personageService';
import { AttributeRepository } from '../../repository/attributeRepository';
import { Quill, QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Attribute } from '../../entity/attribute';
import { NumberOfAttribute } from '../../entity/numberofattribute';
import { PointRepository } from '../../repository/pointRepository';
import { Point } from '../../entity/point';
import { NumberOfPoint } from '../../entity/numberofpoint';
import { Image } from '../../entity/image';
import BasicInput from '../form/basicInput.vue';
import PersonageSheet from './personageSheet.vue';
import { mapState } from 'pinia';
import { useUserStore } from '../../store/user';

export default defineComponent({
    data() {
        return {
            personageService: new PersonageService() as PersonageService,
            attributeRepository: new AttributeRepository() as AttributeRepository,
            pointRepository: new PointRepository() as PointRepository,
            personages: [] as Personage[],
            currentPersonage: {} as Personage,
            isDisplayed: false as boolean,
            isModification: false as boolean,
            key: -1 as number
            
        }
    },
    computed: {
        ...mapState(useUserStore, [
            'getWorld',
            'isGameMaster',
            'getPlayers',
            'getUserId'
        ])
    },
    components: {
        QuillEditor,
        BasicInput,
        PersonageSheet
    },
    methods: {
        viewPersonage: function(personage: Personage, key: number) {
            this.currentPersonage = personage;
            this.isDisplayed = true;
            this.isModification = true;
            this.key = key;
            /*if(!this.currentPersonage.image){
                this.currentPersonage.image = {} as Image;
            }
            //(this.$refs.currentPersonageName as HTMLElement).classList.remove('is-error');
            this.isDisplayed = true;
            (this.$refs.inventory as Quill).setHTML(this.currentPersonage.inventory);
            (this.$refs.biography as Quill).setHTML(this.currentPersonage.biography);*/
        },
        inventoryChange: function() {
            this.currentPersonage.inventory = (this.$refs.inventory as Quill).getHTML()
        },
        biographyChange: function() {
            this.currentPersonage.biography = (this.$refs.biography as Quill).getHTML()
        },
        cancel: function() {
            this.isDisplayed = false;
        },
        updatePersonage: function() {
            if(this.checkForm()) {
                this.isDisplayed = false;
                this.personages[this.key] = this.currentPersonage;
                if(this.isModification) {
                    this.personageService.updatePersonagePartially(this.currentPersonage);
                }else {
                    this.personageService.createPersonage(this.currentPersonage).then((res) => {
                        this.personages.push(res)
                    })
                }
                //(this.$refs.image as HTMLInputElement).value = ''
            }
        },
        createPersonage: function() {
            let personage: Personage = {};
            personage.inventory = '';
            personage.biography = '';
            personage.image = {} as Image;
            personage.world = '/api/worlds/' + this.getWorld.id;
            personage.items = [];
            personage.spells = [];
            this.attributeRepository.findAttributeByWorld(this.getWorld.id).then((attributes) => {
                personage.numberOfAttributes = [];
                attributes.forEach((attribute: Attribute) => {
                    let numberOfAttribute: NumberOfAttribute = {};
                    numberOfAttribute.attribute = attribute;
                    numberOfAttribute.value = 0;
                    personage.numberOfAttributes?.push(numberOfAttribute);
                });
                this.pointRepository.findPointByWorld(this.getWorld.id).then((points) => {
                    personage.numberOfPoints = [];
                    points.forEach((point: Point) => {
                        let numberOfPoint: NumberOfPoint = {};
                        numberOfPoint.point = point;
                        numberOfPoint.min = 0;
                        numberOfPoint.current = 0;
                        numberOfPoint.max = 0;
                        personage.numberOfPoints.push(numberOfPoint);
                    })
                    this.currentPersonage = personage;
                    this.isModification = false;
                    this.isDisplayed = true;
                })
            })
        },
        deletePersonage: function() {
            this.personageService.deletePersonage(this.currentPersonage.id!);
            let index = this.personages.indexOf(this.currentPersonage);
            this.personages.splice(index, 1);
            this.isDisplayed = false;
        },
        checkForm: function() {
            /*if(!this.currentPersonage.name || this.currentPersonage.name?.length! < 2) {
                (this.$refs.currentPersonageName as HTMLElement).classList.add('is-error')
                return false;
            } else {
                (this.$refs.currentPersonageName as HTMLElement).classList.remove('is-error')
                return true;
            }*/
            return true;
        },
        previewFiles: function(e) {
            this.currentPersonage.image.imageFile = e.target.files[0];
        },
        expandParameter: function(e: MouseEvent) {
            let target = e.currentTarget as HTMLElement;
            if(target.parentElement?.classList.contains('expanded')) {
                target.parentElement?.classList.remove('expanded');
                (target.children[1] as HTMLImageElement).src = 'build/images/icons/expand_less.svg'
            }else {
                let formParts = document.getElementsByClassName('form-part');
                for(let i = 0; i < formParts.length; i++) {
                    formParts[i].classList.remove('expanded');
                    (formParts[i].children[0].children[1] as HTMLImageElement).src = 'build/images/icons/expand_less.svg'
                }
                target.parentElement?.classList.add('expanded');
                (target.children[1] as HTMLImageElement).src = 'build/images/icons/expand_more.svg'
            }
        }
    },
    mounted() {
        if(this.isGameMaster) {
            this.personageService.findPersonagesByWorld(this.getWorld.id).then(res => {
                this.personages = res;
            })
        }else {
            this.personageService.findPersonagesByWorldAndByUser(this.getWorld.id, this.getUserId).then(res => {
                this.personages = res;
            })
        }
    }
})

</script>

<style scoped>

    .personages-box {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow-y: scroll;
    }

    .personages {
        flex-grow: 1;
    }

    .personage {
        display: flex;
        border: solid 1px #73808C;
        margin: 8px 0;
    }

    .personage-info {
        padding: 8px;
    }

    .personage-name {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .personage-category {
        display: block;
        margin-top: 4px;
    }

    .me {
        color: #4B7381;
        font-weight: 700;
    }

    .pj {
        color: #4B7381;
    }

    .pnj {
        color: #96724B;
    }

    .parameters-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 5;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100dvh;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .parameters {
        display: block;
        min-width: 256px;
        width: 800px;
        min-height: 256px;
        height: 100%;
        max-height: 92%;
        background-color: #FFFFFF;
    }

    .parameters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border-bottom: solid 1px #73808C;
        background-color: #BBBFC3;
        height: 40px;
    }

    .parameters-body {
        display: flex;
        flex-direction: column;
        height: calc(100% - 88px);
        width: 100%;
        padding: 24px;
    }

    .parameters-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 48px;
        gap: 8px;
        padding: 8px;
        border-top: solid 1px #73808C;
    }

    h3 {
        font-size: 1.5rem;
        padding-bottom: 4px;
        margin-bottom: 8px;
    }

    .dark .personage {
        border: solid 1px #BBBFC3;
    }

    .dark .parameters-header {
        background-color: #0E1318;
    }

    .dark .parameters-body, .dark .parameters-footer {
        background-color: #4F5A64;
    }

    .btn {
        width: 100%;
    }

</style>