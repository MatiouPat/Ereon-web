<template>
    <div class="item-wrapper">
        <div class="item-list">
            <div class="item-header">
                    <h2 v-if="itemType === 1">Liste des armes</h2>
                    <h2 v-else-if="itemType === 2">Liste des armures</h2>
                    <h2 v-else-if="itemType === 3">Liste des objets</h2>
                    <h2 v-else>Liste des sorts</h2>
                    <img width="24" height="24" @click="$emit('close')" src="build/images/icons/close.svg" alt="Fermer">
            </div>   
            <div class="item-body" v-if="itemType === 1">
                <div class="item-body-exist">
                    <h3>Armes existantes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Nom</th>
                                <th style="text-align: left;">Attribut</th>
                                <th style="text-align: left;">Description</th>
                                <th style="text-align: right;">Distance</th>
                                <th style="text-align: left;">Dégât</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="key" v-for="(weaponPrefab, key) in weaponPrefabs" @click="$emit('add:item', weaponPrefab)">
                                <td style="text-align: left;">{{ weaponPrefab.name }}</td>
                                <td style="text-align: left;"><span :key="key" v-for="(attribute, key) in weaponPrefab.attributes">{{ attribute.acronym }}</span></td>
                                <td style="text-align: left;">{{ weaponPrefab.description }}</td>
                                <td style="text-align: right;">{{ weaponPrefab.scope }}</td>
                                <td style="text-align: left;"><ul><li :key="key" v-for="(damage, key) in weaponPrefab.damages">{{ damage.value }}</li></ul></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="item-body-new">
                    <h3>Nouvelle arme</h3>
                    <div class="item-form">
                        <div class="item-form-1">
                            <div class="item-form-1">
                                <basic-input :label="'Nom'" :model-value="newWeaponPrefab.name" @update:model-value="modelValue => newWeaponPrefab.name = modelValue"></basic-input>
                                <select-input :label="'Attribut'" :model-value="newWeaponPrefab.attributes[0].id" :choices="attributesChoice" :background="getIsDarkTheme ? '#4F5A64' : '#FFFFFF'" @update:model-value="(modelValue) => newWeaponPrefab.attributes[0] = '/api/attributes/' + modelValue"></select-input>
                                <basic-input :label="'Distance'" :model-value="newWeaponPrefab.scope" :is-number="true" @update:model-value="modelValue => newWeaponPrefab.scope = modelValue"></basic-input>
                                <basic-input :label="'Dégât'" :model-value="newWeaponPrefab.damages[0].value" @update:model-value="modelValue => newWeaponPrefab.damages[0].value = modelValue"></basic-input>
                            </div>
                            <text-input :label="'Description'" :model-value="newWeaponPrefab.description" @update:model-value="modelValue => newWeaponPrefab.description = modelValue"></text-input>
                        </div>
                    </div>
                    <button class="btn btn-primary" @click="createItemAndLink">Créer et lier l'arme</button>
                </div>
            </div>
            <div class="item-body" v-else-if="itemType === 2">
                <div class="item-body-exist">
                    <h3>Armures existantes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Nom</th>
                                <th style="text-align: left;">Description</th>
                                <th style="text-align: left;">Classe d'armure</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="key" v-for="(armorPrefab, key) in armorPrefabs" @click="$emit('add:item', armorPrefab)">
                                <td style="text-align: left;">{{ armorPrefab.name }}</td>
                                <td style="text-align: left;">{{ armorPrefab.description }}</td>
                                <td style="text-align: left;"><span :key="key" v-for="(resistance, key) in armorPrefab.resistances">{{ resistance.value }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="item-body-new">
                    <h3>Nouvelle armure</h3>
                    <div class="item-form">
                        <div class="item-form-1">
                            <div>
                                <basic-input :label="'Nom'" :model-value="newArmorPrefab.name" @update:model-value="modelValue => newArmorPrefab.name = modelValue"></basic-input>
                                <basic-input :label="'Classe d\'armure'" :model-value="newArmorPrefab.resistances[0].value" @update:model-value="modelValue => newArmorPrefab.resistances[0].value = modelValue"></basic-input>
                            </div>
                            <text-input :label="'Description'" :model-value="newArmorPrefab.description" @update:model-value="modelValue => newArmorPrefab.description = modelValue"></text-input>
                        </div>
                    </div>
                    <button class="btn btn-primary" @click="createItemAndLink">Créer et lier l'armure</button>
                </div>
            </div>
            <div class="item-body" v-else-if="itemType === 3">
                <div class="item-body-exist">
                    <h3>Objets existants</h3>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Nom</th>
                                <th style="text-align: left;">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="key" v-for="(itemPrefab, key) in itemPrefabs" @click="$emit('add:item', itemPrefab)">
                                <td style="text-align: left;">{{ itemPrefab.name }}</td>
                                <td style="text-align: left;">{{ itemPrefab.description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="item-body-new">
                    <h3>Nouvel objet</h3>
                    <div class="item-form">
                        <div class="item-form-1">
                            <basic-input :label="'Nom'" :model-value="newItemPrefab.name" @update:model-value="modelValue => newItemPrefab.name = modelValue"></basic-input>
                            <text-input :label="'Description'" :model-value="newItemPrefab.description" @update:model-value="modelValue => newItemPrefab.description = modelValue"></text-input>
                        </div>
                    </div>
                    <button class="btn btn-primary" @click="createItemAndLink">Créer et lier l'objet</button>
                </div>
            </div>
            <div class="item-body" v-else>
                <div class="item-body-exist">
                    <h3>Sorts existants</h3>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Nom</th>
                                <th style="text-align: left;">Attribut</th>
                                <th style="text-align: right;">Distance</th>
                                <th style="text-align: left;">Description</th>
                                <th style="text-align: left;">Dépense</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="key" v-for="(spell, key) in spells" @click="$emit('add:spell', spell)">
                                <td style="text-align: left;">{{ spell.name }}</td>
                                <td style="text-align: left;">{{ spell.attributes[0].acronym }}</td>
                                <td style="text-align: right;">{{ spell.scope }}</td>
                                <td style="text-align: left;" v-html="spell.description"></td>
                                <td style="text-align: left;">{{ spell.expenses[0].value }} {{ spell.expenses[0].point.acronym }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="item-body-new">
                    <h3>Nouveau Sort</h3>
                    <div class="item-form">
                        <div class="item-form-1">
                            <div class="item-form-1">
                                <basic-input :label="'Nom'" :model-value="newSpell.name" @update:model-value="modelValue => newSpell.name = modelValue"></basic-input>
                                <select-input :label="'Attribut'" :model-value="newSpell.attributes[0].id" :choices="attributesChoice" :background="getIsDarkTheme ? '#4F5A64' : '#FFFFFF'" @update:model-value="(modelValue) => newSpell.attributes[0] = '/api/attributes/' + modelValue"></select-input>
                                <basic-input :label="'Distance'" :is-number="true" :model-value="newSpell.scope" @update:model-value="modelValue => newSpell.scope = modelValue"></basic-input>
                                <div>
                                    <span>Dépense</span>
                                    <basic-input :label="'Valeur'" :is-number="true" :model-value="newSpell.expenses[0].value" @update:model-value="modelValue => newSpell.expenses[0].value = modelValue"></basic-input>
                                    <select-input :label="'Point'" :model-value="newSpell.expenses[0].point.id" :choices="pointsChoice" :background="getIsDarkTheme ? '#4F5A64' : '#FFFFFF'" @update:model-value="(modelValue) => newSpell.expenses[0].point = '/api/points/' + modelValue"></select-input>
                                </div>
                            </div>
                            <text-input :label="'Description'" :model-value="newSpell.description" @update:model-value="modelValue => newSpell.description = modelValue"></text-input>
                        </div>
                    </div>
                    <button class="btn btn-primary" @click="createItemAndLink">Créer et lier le sort</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import basicInput from '../form/basicInput.vue';
import SelectInput from '../form/selectInput.vue';
import { AttributeService } from '../../services/attributeService';
import { WeaponPrefabService } from '../../services/weaponprefabService';
import { WeaponPrefab } from '../../entity/weaponprefab';
import { ArmorPrefab } from '../../entity/armorprefab';
import { ArmorPrefabService } from '../../services/armorprefabService';
import { ItemPrefab } from '../../entity/itemprefab';
import { ItemPrefabService } from '../../services/itemprefabService';
import { Spell } from '../../entity/spell';
import { PointService } from '../../services/pointService';
import { SpellService } from '../../services/spellService';
import TextInput from '../form/textInput.vue';
import { mapState } from 'pinia';
import { useUserStore } from '../../store/user';

export default defineComponent({
    data() {
        return {
            weaponPrefabService: new WeaponPrefabService as WeaponPrefabService,
            attributeService: new AttributeService as AttributeService,
            armorPrefabService: new ArmorPrefabService as ArmorPrefabService,
            itemPrefabService: new ItemPrefabService as ItemPrefabService,
            pointService: new PointService as PointService,
            spellService: new SpellService as SpellService,
            weaponPrefabs: [] as WeaponPrefab[],
            armorPrefabs: [] as ArmorPrefab[],
            itemPrefabs: [] as ItemPrefab[],
            spells: [] as Spell[],
            attributesChoice: [] as {
                id: string
                value: string
            }[],
            pointsChoice: [] as {
                id: string
                value: string
            }[],
            newWeaponPrefab: { scope: 0, damages: [{type: '/api/damage_or_resistance_types/1'}], attributes: [{}] } as WeaponPrefab,
            newArmorPrefab: { resistances: [{type: '/api/damage_or_resistance_types/1'}] } as ArmorPrefab,
            newItemPrefab: {} as ItemPrefab,
            newSpell: { attributes: [{}], expenses: [{point: ''}] } as Spell
        }
    },
    props: {
        itemType: {
            type: Number
        }
    },
    computed: {
        ...mapState(useUserStore, [
            'getWorld',
            'getIsDarkTheme'
        ])
    },
    methods: {
        createItemAndLink: function() {
            if(this.itemType === 1) {
                this.newWeaponPrefab.world = '/api/worlds/' + this.getWorld.id;
                this.weaponPrefabService.createWeaponPrefab(this.newWeaponPrefab).then(weaponPrefab => {
                    this.$emit('add:item', weaponPrefab);
                })
            }else if(this.itemType === 2) {
                this.newArmorPrefab.world = '/api/worlds/' + this.getWorld.id;
                this.armorPrefabService.createArmorPrefab(this.newArmorPrefab).then(armorPrefab => {
                    this.$emit('add:item', armorPrefab);
                })
            }else if(this.itemType === 3) {
                this.newItemPrefab.world = '/api/worlds/' + this.getWorld.id;
                this.itemPrefabService.createItemPrefab(this.newItemPrefab).then(itemPrefab => {
                    this.$emit('add:item', itemPrefab);
                })
            }else if(this.itemType === 4) {
                this.newSpell.world = '/api/worlds/' + this.getWorld.id;
                this.spellService.createSpell(this.newSpell).then(spell => {
                    this.$emit('add:spell', spell);
                })
            }
        }
    },
    components: {
        basicInput,
        SelectInput,
        TextInput
    },
    emits: ['close', 'add:item', 'add:spell'],
    mounted() {
        this.weaponPrefabService.findWeaponPrefabByWorld(this.getWorld.id).then(res => {
            this.weaponPrefabs = res;
        })
        this.attributeService.findAttributeByWorld(this.getWorld.id).then(attributes => {
            attributes.forEach(attribute => {
                this.attributesChoice.push({
                    id: attribute.id.toString(),
                    value: attribute.acronym!
                });
            });
        })
        this.armorPrefabService.findArmorPrefabByWorld(this.getWorld.id).then(res => {
            this.armorPrefabs = res;
        })
        this.itemPrefabService.findItemPrefabByWorld(this.getWorld.id).then(res => {
            this.itemPrefabs = res;
        })
        this.spellService.findSpellByWorld(this.getWorld.id).then(res => {
            this.spells = res;
        })
        this.pointService.findPointByWorld(this.getWorld.id).then(points => {
            points.forEach(point => {
                this.pointsChoice.push({
                    id: point.id!.toString(),
                    value: point.acronym!
                });
            })
        })
        let itemBody = document.getElementsByClassName('item-body')[0] as HTMLElement
        let itemBodyExist = itemBody.children[0] as HTMLElement
        let itemBodyNew = itemBody.children[1] as HTMLElement
        let tbody = itemBodyExist.getElementsByTagName('tbody')[0] as HTMLTableSectionElement
        tbody.style.height = (itemBody.offsetHeight - itemBodyNew.offsetHeight - 111) + 'px'
    },
})

</script>

<style scoped>

h3 {
    font-size: 1.2rem;
    margin-bottom: 8px;
}

.item-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100dvh;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 10;
}

.item-list {
    display: block;
    min-width: 256px;
    width: 800px;
    min-height: 256px;
    height: 100%;
    max-height: 80%;
    background-color: #FFFFFF;
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px;
    border-bottom: solid 1px #73808C;
    background-color: #BBBFC3;
    height: 40px;
}

.item-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: calc(100% - 40px);
    width: 100%;
    padding: 24px;
}
.item-body-new {
    border-top: solid 2px #090D11;
    background-color: #FFFFFF;
    padding-top: 8px;
    margin-top: 8px;
}

.item-body-new .btn {
    margin-left: auto;
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
    border-style: solid;
    border-width: 1px 3px;
    border-color: #FFFFFF;
}

tr {
    margin: 1px 0;
}

td {
    background-color: #F3F4F4;
}

tbody {
    display: block;
    max-height: 100%;
    overflow-y: auto;
}

thead, tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

.item-form {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    padding-bottom: 16px;
}

.item-form-1 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 16px;
    row-gap: 8px;
    width: 100%;
}

.dark .item-header {
    background-color: #0E1318;
}

.dark .item-body {
    background-color: #4F5A64;
}

.dark th {
    background: #1B2229;
    border-color: #4F5A64;
}

.dark td {
    background-color: #364049;
    border-color: #4F5A64;
}

.dark .item-body-new {
    border-color: #F3F4F4;
    background-color: #4F5A64;
}

tbody tr:hover td {
    background-color: #D87D40;
}

</style>