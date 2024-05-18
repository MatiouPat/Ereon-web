<template>
    <div class="map-box" v-if="isGameMaster" :class="{isDisplayed: isDisplayed}">
        <div class="maps">
            <div class="map" @click="chooseMap(map.id)" v-for="(map, key) in maps" :key="key" :style="map.id == getCurrentMapId ? 'border: solid 4px #D68836' : ''">
                <span>{{ map.name }}</span>
                <ul class="map-actions">
                    <li><img @click.stop="showMapDelete(key)" :src="getIsDarkTheme ? '/build/images/icons/delete_white.svg' : '/build/images/icons/delete_black.svg'" alt="Supprimer" width="16" height="16"></li>
                    <li><img @click.stop="showMapParameter(key)" :src="getIsDarkTheme ? '/build/images/icons/settings_white.svg' : '/build/images/icons/settings_black.svg'" alt="Paramètres" width="16" height="16"></li>
                </ul>
            </div>
            <div class="map new-map" @click="createMap()">
                <span>Nouvelle carte</span>
            </div>
        </div>
        <button class="map-open" name="menu" type="button" :class="{isDisplayed: isDisplayed}" @click="display">
            <svg width="32" height="32" viewBox="0 0 100 100">
                <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
                <path class="line line2" d="M 20,50 H 80" />
                <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
            </svg>
        </button>
        <modal
            :isDisplayed="isParametersDisplayed"
            :modalTitleMessage="'Informations carte'"
            :modalValidationMessage="onCreation ? 'Créer la carte' : 'Modifier la carte'"
            @modal:close="isParametersDisplayed = false"
            @modal:validation="submitForm"
        >
            <template v-slot:modal-body>
                <div>
                    <h3>Positionnement</h3>
                    <div class="form-part">
                        <basic-input :model-value="map.name" :label="'Nom'"  @update:model-value="(modelValue) => map.name = modelValue"></basic-input>
                        <div class="row">
                            <div class="field">
                                <label>Dynamic light</label>
                                <input v-model="map.hasDynamicLight" type="checkbox">
                            </div>
                        </div>
                        <basic-input :is-number="true" :model-value="map.width" :label="'Largeur'"  @update:model-value="(modelValue) => map.width = modelValue"></basic-input>
                        <basic-input :is-number="true" :model-value="map.height" :label="'Hauteur'"  @update:model-value="(modelValue) => map.height = modelValue"></basic-input>
                    </div>
                </div>
                <div v-if="connections.length">
                    <h3>Joueurs</h3>
                    <div v-for="connection in connections" :key="connection.id">
                        <label>{{ connection.user.username }}</label>
                        <input type="checkbox" :value="connection" :checked="isChecked(connection)" @change.prevent="updateConnection(connection)">
                    </div>
                </div>
                <div v-else >
                    <h3>Joueurs</h3>
                    <span>Aucun joueur n'est présent sur cette partie</span>
                </div>
            </template>
        </modal>
        <modal
            :isDisplayed="isDeleteDisplayed"
            :type="'popup'"
            :modalValidationMessage="'Supprimer la carte'"
            @modal:close="isDeleteDisplayed = false"
            @modal:validation="deleteMap"
        >
            <template v-slot:modal-body>
                <span>Voulez-vous supprimer la carte ?</span>
            </template>
        </modal>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Connection } from '../entity/connection';
import { Map } from '../entity/map';
import { MapService } from '../services/mapService';
import { ConnectionService } from '../services/connectionService';
import basicInput from './forms/inputs/basicInput.vue';
import Modal from './modal/modal.vue';
import { mapActions, mapState } from 'pinia';
import { useMapStore } from '../store/map';
import { useUserStore } from '../store/user';

    export default defineComponent({
        data() {
            return {
                mapService: new MapService as MapService,
                connectionService: new ConnectionService as ConnectionService,
                /**
                 * If the maps are displayed
                 */
                isDisplayed: false as boolean,
                /**
                 * If the map parameters box is displayed
                 */
                isParametersDisplayed: false as boolean,
                isDeleteDisplayed: false as boolean,
                /**
                 * The map parameters related to the form
                 */
                map: {} as Map,
                maps: [] as Map[],
                /**
                 * The list of connections between this world and the various users
                 */
                connections: [] as Connection[],
                onCreation: false as boolean,
                chosenKey: 0 as number
            }
        },
        computed: {
            ...mapState(useMapStore, {
                getMap: 'map'
            }),
            ...mapState(useUserStore, [
                'isGameMaster',
                'getCurrentMapId',
                'getPlayers',
                "getWorld",
                'getIsDarkTheme'
            ]),
        },
        components: { basicInput, Modal },
        methods: {
            ...mapActions(useUserStore, [
                'setCurrentMap'
            ]),
            ...mapActions(useMapStore, [
                'setMap'
            ]),
            /**
             * Display maps
             */
            display: function () {
                this.isDisplayed = !this.isDisplayed;
                window.addEventListener('click', this.clickOutside)
            },
            /**
             * Change current map 
             * @param {*} mapId 
             */
            chooseMap: function (mapId: number) {
                this.mapService.findMapById(mapId).then(map => {
                    this.setMap(map);
                })
                this.setCurrentMap(mapId)
                this.isDisplayed = false;
            },
            /**
             * Display map parameters by calling the API
             * @param {*} key 
             */
            showMapParameter: function (key: number) {
                this.onCreation = false;
                this.map = this.maps[key];
                this.connections = [];
                this.getPlayers.forEach((player: Connection) => {
                    this.connections.push(player)
                });
                this.isParametersDisplayed = true;
            },
            showMapDelete: function(key: number) {
                this.isDeleteDisplayed = true;
                this.map = this.maps[key];
                this.chosenKey = key;
            },
            createMap: function() {
                this.onCreation = true;
                this.map = {name: '', width: 0, height: 0, hasDynamicLight: false, connections: [], world: '/api/worlds/' + this.getWorld.id};
                this.connections = [];
                this.getPlayers.forEach((player: Connection) => {
                    this.connections.push(player)
                });
                this.isParametersDisplayed = true;
            },
            isChecked: function(searchConnection: Connection) {
                return this.map.connections.some(
                    (connection: Connection) => connection.id === searchConnection.id
                );
            },
            updateConnection: function(connection: Connection) {
                if(!this.isChecked(connection)) {
                    this.map.connections.push(connection)
                }
                return
            },
            cancel: function() {
                this.isParametersDisplayed = false;
                this.mapService.findAllMaps().then(maps => {
                    this.maps = maps;
                })
            },
            /**
             * Change map settings after form submission
             */
            submitForm: function() {
                if(this.onCreation) {
                    this.mapService.createMap(this.map);
                }else {
                    this.mapService.updateMapPartially(this.map);
                }
                this.isParametersDisplayed = false;
                this.isDisplayed = false;
                this.mapService.findAllMaps().then(maps => {
                    this.maps = maps;
                })
            },
            /**
             * Hide context box when clicked outside it
             * @param {*} e 
             */
            clickOutside: function(e: MouseEvent) {
                if(!this.$el.contains(e.target)){
                    window.removeEventListener('click', this.clickOutside)
                    this.isDisplayed = false;
                }
            },
            deleteMap: function() {
                if(this.maps.length > 1){
                    if(this.maps[this.chosenKey].id === this.getMap.id) {
                        if(this.chosenKey == 0) {
                            this.setMap(this.maps[1]);
                        }else {
                            this.setMap(this.maps[0]);
                        }
                    }
                    this.mapService.deleteMap(this.map.id);
                    this.maps.splice(this.chosenKey, 1);
                }
                this.isDeleteDisplayed = false;
            }
        },
        mounted() {
            this.mapService.findAllMaps().then(maps => {
                this.maps = maps;
            })
        }
    })
</script>

<style scoped>

    h3 {
        font-size: 1.2rem;
    }

    .map-box {
        position: absolute;
        top: -256px;
        left: 10%;
        width: 80%;
        height: 256px;
        z-index: 5;
        background-color: #FFF;
        transition: .1s ease-in-out;
    }

    .map-box.isDisplayed {
        top: 0;
    }

    .map-open {
        position: absolute;
        bottom: -40px;
        left: 80%;
        width: 40px;
        height: 40px;
        background-color: #D87D40;
        border: none;
        cursor: pointer;
        padding: 4px;
    }

    .line {
        fill: none;
        stroke: #FFF;
        stroke-width: 6;
        transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
            stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .line1 {
        stroke-dasharray: 60 207;
        stroke-width: 6;
    }

    .line2 {
        stroke-dasharray: 60 60;
        stroke-width: 6;
    }

    .line3 {
        stroke-dasharray: 60 207;
        stroke-width: 6;
    }

    .isDisplayed .line1 {
        stroke-dasharray: 90 207;
        stroke-dashoffset: -134;
        stroke-width: 6;
    }

    .isDisplayed .line2 {
        stroke-dasharray: 1 60;
        stroke-dashoffset: -30;
        stroke-width: 6;
    }

    .isDisplayed .line3 {
        stroke-dasharray: 90 207;
        stroke-dashoffset: -134;
        stroke-width: 6;
    }

    .maps {
        display: flex;
        gap: 16px;
        width: 100%;
        height: 100%;
        overflow-y: scroll;
        padding: 8px;
    }

    .maps::-webkit-scrollbar {
        width: .4em;
    }
    
    .maps::-webkit-scrollbar-thumb {
        background-color: #666666;
    }

    .map {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #73808C;
        width: 128px;
        height: 128px;
        cursor: pointer;
    }

    .new-map {
        background-color: #D87D40;
    }

    .map:hover .map-actions {
        opacity: 1;
    }

    .map-actions {
        position: absolute;
        left: calc(50% - 16px);
        bottom: 0;
        display: flex;
        gap: 4px;
        opacity: 0;
        width: 40px;
        -webkit-transition: opacity 100ms ease-in-out;
        -moz-transition: opacity 100ms ease-in-out;
        -o-transition: opacity 100ms ease-in-out;
        transition: opacity 100ms ease-in-out;
    }

    .map-actions li {
        cursor: pointer;
    }

    .form-part {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 16px;
        row-gap: 8px;
    }

    .dark .map-box {
        background-color: #1F262D;
    }

    .dark .map {
        background-color: #364049;
    }

    .dark .new-map {
        background-color: #D87D40;
    }

</style>