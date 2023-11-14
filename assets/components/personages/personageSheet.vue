<template>
    <div class="personage-header" v-if="isInitialize">
        <image-input :modelImage="personage.image" :width="192" :height="192" @update:modelImage="(modelImage) => personage.image = modelImage"></image-input>
        <div class="personage-header-info">
            <div class="personage-main-info">
                <basic-input :model-value="personage.name" :label="'Nom'" @update:model-value="(modelValue) => personage.name = modelValue"></basic-input>
                <select-input :model-value="personageUser" :choices="userChoices" :label="'Joueur'" :background="getIsDarkTheme ? '#4F5A64' : '#FFFFFF'" @update:model-value="(modelValue) => personageUser = modelValue"></select-input>
            </div>
            <div class="personage-points">
                <div class="personage-point" :key="key" v-for="(numberOfPoint, key) in personage.numberOfPoints">
                    <div class="personage-point-max">
                        <span class="personage-point-max-label">MAX</span>
                        <basic-input class="personage-point-form" :is-number="true" :model-value="numberOfPoint.max" @update:model-value="(modelValue) => numberOfPoint.max = modelValue"></basic-input>
                    </div>
                    <div class="personage-point-title">
                        <span>{{ numberOfPoint.point.acronym }}</span>
                        <img :src="getIsDarkTheme ? '/build/images/sheets/point_white.svg' : '/build/images/sheets/point_black.svg'" alt="" width="80" height="80">
                        <div class="point-liquid" :style="{ backgroundColor: (numberOfPoint.point.acronym === 'PV' ? '#e63946' : '#457b9d'), clipPath: 'inset(' + (100 - numberOfPoint.current * 100 / numberOfPoint.max) + '% 0 0 0)'}"></div>
                    </div>
                    <basic-input class="personage-point-form" :is-number="true" :model-value="numberOfPoint.current" @update:model-value="(modelValue) => numberOfPoint.current = modelValue"></basic-input>
                </div>
                <div class="personage-shield">
                    <span>Armure</span>
                    <img :src="getIsDarkTheme ? '/build/images/sheets/shield_white.svg' : '/build/images/sheets/shield_black.svg'" alt="" width="80" height="80">
                </div>
            </div>
        </div>
    </div>
    <div class="personage-navigation">
        <h2 class="personage-navigation-title">{{ pages[pageIndex] }}</h2>
        <ul>
            <li :class="pageIndex === 0 ? 'selected' : null" @click="pageIndex = 0">
                <img :src="getIsDarkTheme ? '/build/images/sheets/statistics_white.svg' : '/build/images/sheets/statistics_black.svg'" alt="Statistiques" width="24" height="24">
            </li>
            <li :class="pageIndex === 1 ? 'selected' : null" @click="pageIndex = 1">
                <img :src="getIsDarkTheme ? '/build/images/sheets/inventory_white.svg' : '/build/images/sheets/inventory_black.svg'" alt="Inventaire" width="24" height="24">
            </li>
            <li :class="pageIndex === 2 ? 'selected' : null" @click="pageIndex = 2">
                <img :src="getIsDarkTheme ? '/build/images/sheets/spells_white.svg' : '/build/images/sheets/spells_black.svg'" alt="Sorts" width="20" height="20">
            </li>
            <li :class="pageIndex === 3 ? 'selected' : null" @click="pageIndex = 3">
                <img :src="getIsDarkTheme ? '/build/images/sheets/secondary_information_white.svg' : '/build/images/sheets/secondary_information_black.svg'" alt="Informations secondaires" width="24" height="24">
            </li>
        </ul>
    </div>
    <div class="personage-body">
        <div v-show="pageIndex === 0" class="personage-body-sections">
            <section>
                <h3>Attributs</h3>
                <div class="personage-attributes">
                    <div class="personage-attribute" :key="key" v-for="(numberOfAttribute, key) in personage.numberOfAttributes">
                        <basic-input :label="numberOfAttribute.attribute.name" :is-number="true" :modelValue="numberOfAttribute.value" @update:model-value="(modelValue) => numberOfAttribute.value = modelValue"></basic-input>
                    </div>
                </div>
            </section>
            <section>
                <h3>Compétences</h3>
                <div>

                </div>
            </section>
        </div>
        <div v-show="pageIndex === 1">
            <div>
                <h3>Armes</h3>
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: left;">Nom</th>
                            <th style="text-align: left;">Attribut</th>
                            <th style="text-align: left;">Dégât</th>
                            <th style="text-align: left;">Lancer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :key="key" v-for="(weapon, key) in weapons">
                            <td style="text-align: left;">{{ weapon.itemPrefab.name }}</td>
                            <td style="text-align: left;">{{ weapon.itemPrefab.attributes[0].acronym }}</td>
                            <td style="text-align: left;">{{ weapon.itemPrefab.damages[0].value }}</td>
                            <td style="text-align: left;"><img v-if="weapon.itemPrefab.damages[0]" @click="rollDice(weapon.itemPrefab.damages[0].value)" width="16" height="16" :src="getIsDarkTheme ? '/build/images/d6_white.svg' : '/build/images/d6_black.svg'" alt="d6"><span v-else>NaN</span></td>
                        </tr>
                        <tr v-if="!weapons.length">
                            <td colspan="4" style="text-align: center;">Le personage ne possède aucune arme</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <h3>Armures</h3>
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Nom</th>
                                <th style="text-align: right;">Classe d'armure</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="key" v-for="(armor, key) in armors">
                                <td style="text-align: right;">{{ armor.itemPrefab.name }}</td>
                                <td></td>
                            </tr>
                            <tr v-if="!armors.length">
                                <td colspan="2" style="text-align: center;">Le personage ne possède aucune armure</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <h3>Objets</h3>
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Nom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="key" v-for="(item, key) in items">
                                <td style="text-align: left;">{{ item.itemPrefab.name }}</td>
                            </tr>
                            <tr v-if="!items.length">
                                <td colspan="1" style="text-align: center;">Le personage ne possède aucun objet</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-show="pageIndex === 2">
            <h3>Sorts</h3>
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left;">Nom</th>
                        <th style="text-align: left;">Attribut</th>
                        <th style="text-align: right;">Distance&nbsp;(m)</th>
                        <th style="text-align: left;">Description</th>
                        <th style="text-align: left;">Dépense</th>
                        <th style="text-align: left;">Lancer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :key="key" v-for="(spell, key) in personage.spells">
                        <td style="text-align: left;">{{ spell.name }}</td>
                        <td style="text-align: left;">{{ spell.attributes[0].acronym }}</td>
                        <td style="text-align: right;">{{ spell.scope }}</td>
                        <td style="text-align: left;">{{ spell.description }}</td>
                        <td style="text-align: left;">{{ spell.expenses[0].value }} {{ spell.expenses[0].point.acronym }}</td>
                        <td style="text-align: left;"><img v-if="spell.damages[0]" @click="rollDice(spell.damages[0].value)" width="16" height="16" :src="getIsDarkTheme ? '/build/images/d6_white.svg' : '/build/images/d6_black.svg'" alt="d6"><span v-else>NaN</span></td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-primary">Ajouter un sort</button>
        </div>
        <div v-show="pageIndex === 3">
            <basic-input :label="'Race'"></basic-input>
            <basic-input :label="'Classe'"></basic-input>
            <basic-input :label="'Alignement'"></basic-input>
            <text-input></text-input>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import BasicInput from '../form/basicInput.vue';
import ImageInput from '../form/imageInput.vue';
import TextInput from '../form/textInput.vue';
import { Personage } from '../../entity/personage';
import SelectInput from '../form/selectInput.vue';
import { mapGetters } from 'vuex';
import { Connection } from '../../entity/connection';
import { Item } from '../../entity/item';
import { Weapon } from '../../entity/weapon';
import { Armor } from '../../entity/armor';
import { DiceService } from '../../services/diceService';

export default defineComponent({
    data() {
        return {
            diceService: new DiceService() as DiceService,
            personage: {} as Personage,
            weapons: [] as Item[],
            armors: [] as Item[],
            items: [] as Item[],
            pageIndex: 0 as number,
            pages: [
                "STATISTIQUES",
                "INVENTAIRE",
                "SORTS",
                "INFORMATIONS SECONDAIRES"
            ],
            isInitialize: false as boolean,
            personageUser : -1 as number
        }
    },
    watch: {
        personage: {
            handler() {
                this.$emit('update:personage', this.personage)
            },
            flush: 'post'
        },
        personageUser: {
            handler() {
                this.personageUser > -1 ? this.personage.user = '/api/users/' + this.personageUser : this.personage.user = undefined
            },
            flush: 'post'
        }
    },
    props: {
        isDisplayed: {
            type: Boolean,
            default: false
        },  
        currentPersonage: {
            type: Object,
            default: {}
        }
    },
    computed: {
        ...mapGetters('user', [
            'getUserId',
            'getPlayers',
            'getWorld',
            'getIsDarkTheme'
        ]),
        userChoices() {
            let choices = [] as {
                id: string
                value: string
            }[];
            this.getPlayers.forEach((player: Connection) => {
                choices.push({
                    id: player.user.id,
                    value: player.user.username
                });
            });
            return choices;
        }
    },
    methods: {
        rollDice: function(computation: string) {
            this.diceService.createDice(this.getUserId, this.getWorld.id, computation, 0);
        }
    },
    components: {
        BasicInput,
        ImageInput,
        TextInput,
        SelectInput
    },
    emits: ['update:personage'],
    mounted() {
        this.personage = JSON.parse(JSON.stringify(this.currentPersonage));
        if(this.personage.items) {
            this.personage.items.forEach((item: Item) => {
                if(item.itemPrefab['@type'] === 'Weapon') {
                    this.weapons.push(item as Weapon);
                }else if(item.itemPrefab['@type'] === 'Armor') {
                    this.armors.push(item as Armor);
                }else {
                    this.items.push(item);
                }
            });
        }
        if(this.personage.user) {
            this.personageUser = this.personage.user.id;
        }
        this.isInitialize = true;
    }
})
</script>

<style scoped>

h3 {
    font-size: 1.2rem;
    margin-bottom: 8px;
}

table {
    width: 100%;
    line-height: 20px;
    border-collapse: collapse;
}

th {
    font-weight: 700;
    background: #D7D9DB;
}

th, td {
    padding: 8px;
    border: solid 3px #FFFFFF;
}

tr {
    margin: 1px 0;
}

td {
    background-color: #F3F4F4;
}

.personage-header {
    display: flex;
    gap: 16px;
}

.personage-header-info {
    width: 100%;
}

.personage-main-info {
    display: flex;
    gap: 16px;
}

.personage-points {
    display: flex;
    gap: 32px;
    margin: 16px 16px 0 16px;
}

.personage-point-max {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
}

.personage-point-max-label {
    position: absolute;
    top: calc(50% - 7px);
    left: 0;
}

.personage-point-title {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 8px 0;
}

.personage-point-title img {
    z-index: 2;
}

.personage-point-form {
    width: 33%;
    margin-left: 45%;
}

.point-liquid {
    position: absolute;
    bottom: 2px;
    right: 8px;
    width: 70px;
    height: 70px;
    border-radius: 70px;
}

.personage-shield {
    display: flex;
    align-items: center;
}

.personage-navigation {
    position: relative;
    display: flex;
    align-items: center;
    background: #BBBFC3;
    margin-top: 24px;
    padding: 4px;
}

.personage-navigation-title {
    font-weight: 700;
}

.personage-navigation ul {
    position: absolute;
    top: calc(50% - 16px);
    left: 192px;
    display: flex;
    gap: 16px;
}

.personage-navigation li {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 32px;
    height: 32px;
    background: #BBBFC3;
    border: solid 2px #F3F4F4;
    border-radius: 50%;
}

.personage-navigation li.selected {
    border: solid 2px #D68836;
}

.personage-body {
    padding-top: 16px;
}

.personage-body-sections {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.personage-attributes {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    column-gap: 16px;
    row-gap: 8px;
}

.personage-attribute {
    width: 100%;
}

.dark .personage-navigation {
    background: #1F262D;
}

.dark .personage-navigation li {
    background: #1F262D;
    border: solid 2px #363D45;

}

.dark th {
    background: #1B2229;
    border: solid 3px #4F5A64;
}

.dark td {
    background-color: #364049;
    border: solid 3px #4F5A64;
}

</style>