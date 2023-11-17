<template>
    <div class="item-wrapper">
        <div class="item-list">
            <div class="item-header">
                    <h2>Liste des armes</h2>
                    <img width="24" height="24" @click="$emit('close')" src="build/images/icons/close.svg" alt="Fermer">
            </div>   
            <div class="item-body">
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
                <hr>
                <h3>Nouvelle arme</h3>
                <div class="item-form">
                    <basic-input :label="'Nom'" :model-value="newWeaponPrefab.name" @update:model-value="modelValue => newWeaponPrefab.name = modelValue"></basic-input>
                    <select-input :label="'Attribut'" :model-value="newWeaponPrefab.attributes[0].id" :choices="attributesChoice" :background="getIsDarkTheme ? '#4F5A64' : '#FFFFFF'" @update:model-value="(modelValue) => newWeaponPrefab.attributes[0] = '/api/attributes/' + modelValue"></select-input>
                    <basic-input :label="'Description'" :model-value="newWeaponPrefab.description" @update:model-value="modelValue => newWeaponPrefab.description = modelValue"></basic-input>
                    <basic-input :label="'Distance'" :model-value="newWeaponPrefab.scope" :is-number="true" @update:model-value="modelValue => newWeaponPrefab.scope = modelValue"></basic-input>
                    <basic-input :label="'Dégât'" :model-value="newWeaponPrefab.damages[0].value" @update:model-value="modelValue => newWeaponPrefab.damages[0].value = modelValue"></basic-input>
                </div>
                <button class="btn btn-primary" @click="createItemAndLink">Créer et lier l'arme</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { mapGetters } from 'vuex';
import basicInput from '../form/basicInput.vue';
import SelectInput from '../form/selectInput.vue';
import { AttributeService } from '../../services/attributeService';
import { WeaponPrefabService } from '../../services/weaponprefabService';
import { WeaponPrefab } from '../../entity/weaponprefab';

export default defineComponent({
    data() {
        return {
            weaponPrefabService: new WeaponPrefabService as WeaponPrefabService,
            attributeService: new AttributeService as AttributeService,
            weaponPrefabs: [] as WeaponPrefab[],
            attributesChoice: [] as {
                id: string
                value: string
            }[],
            newWeaponPrefab: { scope: 0, damages: [{type: '/api/damage_or_resistance_types/1'}], attributes: [{}] } as WeaponPrefab
        }
    },
    props: {
        itemType: {
            type: Number
        }
    },
    computed: {
        ...mapGetters('user', [
            'getWorld',
            'getIsDarkTheme'
        ])
    },
    methods: {
        createItemAndLink: function() {
            this.newWeaponPrefab.world = '/api/worlds/' + this.getWorld.id;
            this.weaponPrefabService.createWeaponPrefab(this.newWeaponPrefab).then(weaponPrefab => {
                this.$emit('add:item', weaponPrefab);
            })
        }
    },
    components: {
        basicInput,
        SelectInput
    },
    emits: ['close', 'add:item'],
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
    max-height: 50%;
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
    height: calc(100% - 40px);
    width: 100%;
    padding: 24px;
    overflow-y: scroll;
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
.item-form {
    display: flex;
    align-items: flex-end;
    gap: 16px;
}

.dark .item-header {
    background-color: #0E1318;
}

.dark .item-body {
    background-color: #4F5A64;
}

.dark th {
    background: #1B2229;
    border: solid 3px #4F5A64;
}

.dark td {
    background-color: #364049;
    border: solid 3px #4F5A64;
}

tbody tr:hover td {
    background-color: #D87D40;
}

</style>../../services/weaponprefabService../../entity/weaponprefab