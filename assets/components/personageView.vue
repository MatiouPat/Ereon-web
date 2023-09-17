<template>
    <div class="personages-box">
        <div class="personage" :key="key" v-for="(personage, key) in personages" @click="viewPersonage(personage)">
            {{ personage.name }}
        </div>
        <button class="btn btn-primary" type="button" @click="createPersonage">Ajouter</button>
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
                                <div class="form-part">
                                    <div class="form-part-header" @click="expandParameter">
                                        <h3>Informations générales</h3>
                                        <img width="24" height="24" src="build/images/icons/expand_less.svg" alt="">
                                    </div>
                                    <div class="form-part-content">
                                        <div class="form-group" ref="currentPersonageName">
                                            <label class="form-label">Nom<i class="form-required">*</i></label>
                                            <span class="form-error"><img width="16" height="16" src="build/images/icons/warning.svg">Cette chaine de charactère doit être supérieure ou égale à 2 caractères</span>
                                            <input class="form-control" type="text" v-model="currentPersonage.name">
                                        </div>
                                        <div class="form-group">
                                            <label>Joueur</label>
                                            <select v-model="currentPersonage.user">
                                                <option :value="null"></option>
                                                <option :key="key" v-for="(player, key) in getPlayers" :value="'/api/users/' + player.id">{{ player.username }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Race</label>
                                            <input class="form-control" type="text" v-model="currentPersonage.race">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Image</label>
                                            <input class="form-control" type="file" @change="previewFiles" ref="image">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Alignement</label>
                                            <input class="form-control" type="text" v-model="currentPersonage.alignment">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Classe</label>
                                            <input class="form-control" type="text" v-model="currentPersonage.class">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Biographie</label>
                                            <quill-editor class="html-editor" ref="biography" :options="options" @text-change="biographyChange"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-part">
                                    <div class="form-part-header" @click="expandParameter">
                                        <h3>Points (PV, PM, etc...)</h3>
                                        <img width="24" height="24" src="build/images/icons/expand_less.svg" alt="">
                                    </div>
                                    <div class="form-part-content">
                                        <div class="form-points">
                                            <div class="form-group" :key="key" v-for="(numberOfPoint, key) in currentPersonage.numberOfPoints">
                                                <label class="form-label">{{ numberOfPoint.point?.acronym }}</label>
                                                <div class="form-point">
                                                    <div class="form-group">
                                                        <label class="form-label">Min</label>
                                                        <input class="form-control" type="number" v-model="numberOfPoint.min">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Actuel</label>
                                                        <input class="form-control" type="number" v-model="numberOfPoint.current">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Max</label>
                                                        <input class="form-control" type="number" v-model="numberOfPoint.max">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-part">
                                    <div class="form-part-header" @click="expandParameter">
                                        <h3>Attributs</h3>
                                        <img width="24" height="24" src="build/images/icons/expand_less.svg" alt="">
                                    </div>
                                    <div class="form-part-content">
                                        <div class="form-attributes">
                                            <div class="form-group" :key="key" v-for="(numberOfAttribute, key) in currentPersonage.numberOfAttributes">
                                                <label class="form-label">{{ numberOfAttribute.attribute?.name }}</label>
                                                <input class="form-control" type="number" v-model="numberOfAttribute.value">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-part">
                                    <div class="form-part-header" @click="expandParameter">
                                        <h3>Inventaire</h3>
                                        <img width="24" height="24" src="build/images/icons/expand_less.svg" alt="">
                                    </div>
                                    <div class="form-part-content">
                                        <div class="form-group">
                                            <label class="form-label">Inventaire</label>
                                            <quill-editor class="html-editor" ref="inventory" :options="options" @text-change="inventoryChange"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="parameters-footer">
                                <button class="btn btn-secondary" type="button" @click="cancel">Annuler</button>
                                <button class="btn btn-secondary" type="button" v-if="isModification && isGameMaster" @click="deletePersonage">Supprimer</button>
                                <button class="btn btn-primary" type="button" @click="updatePersonage">Valider</button>
                            </div>
                        </div>
                        <div class="personage-view">
                            <div class="personage-view-content" style="background-image: url('build/images/sheets/fond_fiche_bleu_2.webp');">
                                <div class="personage-view-block" style="background-image: url('build/images/sheets/texture_old_paper.png');">
                                    <span class="personage-name">{{ currentPersonage.name }}</span><br>
                                    <p class="personage-main-info" v-if="currentPersonage.class || currentPersonage.race || currentPersonage.alignment"><span>{{ currentPersonage.class }}</span><span>{{ currentPersonage.race }}</span><span v-if="currentPersonage.alignment">, {{ currentPersonage.alignment }}</span></p>
                                    <hr>
                                    <div class="personage-points">
                                        <div class="personage-point" :key="key" v-for="(numberOfPoint, key) in currentPersonage.numberOfPoints">
                                            <span class="personage-attribute-name">{{ numberOfPoint.point?.acronym }}</span>
                                            <span class="personage-attribute-value">{{ numberOfPoint.current }} / {{ numberOfPoint.max }}</span>
                                        </div>
                                    </div>
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
                                <div class="personage-biography">
                                    <span class="personage-name">{{ currentPersonage.name }}</span>
                                    <hr>
                                    <p v-html="currentPersonage.biography"></p>
                                </div>
                                <img class="personage-image" v-if="currentPersonage.image?.imageName" :src="'/uploads/images/personages/' + currentPersonage.image.imageName">
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
import { PersonageService } from '../services/personageService';
import { AttributeRepository } from '../repository/attributeRepository';
import { Quill, QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Attribute } from '../entity/attribute';
import { mapGetters } from 'vuex';
import { NumberOfAttribute } from '../entity/numberofattribute';
import { PointRepository } from '../repository/pointRepository';
import { Point } from '../entity/point';
import { NumberOfPoint } from '../entity/numberofpoint';
import { Image } from '../entity/image';

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
    computed: {
        ...mapGetters('user', [
            'getWorld',
            'isGameMaster',
            'getPlayers'
        ])
    },
    components: {
        QuillEditor
    },
    methods: {
        viewPersonage: function(personage: Personage) {
            this.currentPersonage = personage;
            if(!this.currentPersonage.image){
                this.currentPersonage.image = {} as Image;
            }
            (this.$refs.currentPersonageName as HTMLElement).classList.remove('is-error');
            this.isModification = true;
            this.isDisplayed = true;
            (this.$refs.inventory as Quill).setHTML(this.currentPersonage.inventory);
            (this.$refs.biography as Quill).setHTML(this.currentPersonage.biography);
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
                if(this.isModification) {
                    this.personageService.updatePersonagePartially(this.currentPersonage);
                }else {
                    this.personageService.createPersonage(this.currentPersonage).then((res) => {
                        this.personages.push(res)
                    })
                }
                (this.$refs.image as HTMLInputElement).value = ''
            }
        },
        createPersonage: function() {
            let personage: Personage = {};
            personage.inventory = '';
            personage.biography = '';
            personage.image = {} as Image;
            personage.world = '/api/worlds/' + this.getWorld.id;
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
                    (this.$refs.inventory as Quill).setHTML(this.currentPersonage.inventory);
                    (this.$refs.biography as Quill).setHTML(this.currentPersonage.biography);
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
            if(!this.currentPersonage.name || this.currentPersonage.name?.length! < 2) {
                (this.$refs.currentPersonageName as HTMLElement).classList.add('is-error')
                return false;
            } else {
                (this.$refs.currentPersonageName as HTMLElement).classList.remove('is-error')
                return true;
            }
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
        this.personageService.findAllPersonages().then(res => {
            this.personages = res;
        })
    }
})

</script>

<style scoped>

    .personages-box {
        height: 100%;
        overflow-y: scroll;
    }

    .personage {
        border: solid 1px #565656;
        padding: 8px;
        margin: 8px 0;
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
        height: 100%;
        max-height: 92%;
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
        width: 100%;
        height: 100%;
    }

    .personage-form {
        height: calc(100% - 48px);
        overflow-y: scroll;
        padding: 16px;
    }

    .form-part {
        margin-bottom: 16px;
    }

    .form-part-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: solid 1px #565656;
        margin-bottom: 8px;
    }

    .form-part-content {
        display: none;
    }

    .form-part.expanded .form-part-content {
        display: block;
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
        display: none;
        width: 540px;
        height: 100%;;
        overflow-y: scroll;
        background-color: #FFFFFF;
        color: #423d2a;
        line-height: 20px;
    }

    .personage-view-content {
        position: relative;
        display: flex;
        width: 1080px;
        height: 1396px;
        margin-top: -349px;
        margin-left: -270px;
        background-repeat: no-repeat;
        background-size: cover;
        padding: 48px;
        transform: scale(0.5);
    }

    .personage-view-block {
        position: relative;
        display: block;
        min-width: 256px;
        width: 100%;
        max-width: 400px;
        min-height: 400px;
        height: fit-content;
        max-height: 1200px;
        padding: 16px;
    }

    .personage-biography {
        width: 100%;
        padding-left: 48px;
    }

    .personage-biography hr {
        border: solid 1px #b4ac7e;
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

    .personage-points, .personage-attributes {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        gap: 8px;
        width: 100%;
        font-size: 1.25rem;
        padding: 8px 0;
    }

    .personage-points {
        padding-top: 0;
    }

    .personage-point, .personage-attribute {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .personage-point {
        position: relative;
        padding: 8px 16px;
        width: 88px;
        height: 88px;
    }

    .personage-point::after {
        position: absolute;
        bottom: -16px;
        left: 0;
        height: 100%;
        width: 100%;
        content: "";
        background-image: url('../images/sheets/laurier.svg');
        background-repeat: no-repeat;
        background-size: 100%;
    }

    .personage-attribute-name {
        font-weight: 500;
        color: #8C2417;
    }

    .personage-attribute-value {
        font-weight: 300;
    }

    .personage-view-block hr {
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        color: #8C2417;
        background-color: #8C2417;
        height: 2px;
    }

    .personage-image {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 60%;
    }

    h3 {
        font-size: 1.5rem;
        padding-bottom: 4px;
        margin-bottom: 8px;
    }

    .form-attributes, .form-point {
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

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {
        .personage-parameters {
            width: calc(100% - 540px);
        }

        .personage-view {
            display: block;
        }
    } 

    /* Extra large devices (large laptops and desktops, 1200px and up) */
    @media only screen and (min-width: 1200px) {

        .personage-parameters {
            width: calc(100% - 756px);
        }

        .personage-view {
            width: 756px;
        }

        .personage-view-content {
            margin-top: -209px;
            margin-left: -162px;
            transform: scale(0.7);
        }
    }

    @media only screen and (min-width: 1600px) {

        .personage-parameters {
            width: calc(100% - 1080px);
        }

        .personage-view {
            width: 1080px;
        }

        .personage-view-content {
            margin-top: 0;
            margin-left: 0;
            transform: scale(1);
        }
    }

</style>