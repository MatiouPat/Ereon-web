<template>
    <div class="map-box" v-if="isGameMaster" :class="{isDisplayed: isDisplayed}">
        <div class="maps">
            <div class="map" @click="chooseMap(map.id)" v-for="(map, key) in maps" :key="key" :style="map.id == getCurrentMapId ? 'border: solid 4px #D68836' : ''">
                <span>{{ map.name }}</span>
                <ul class="map-actions">
                    <li><img @click.stop="showMapParameter(key)" :src="getIsDarkTheme ? '/build/images/icons/settings_white.svg' : '/build/images/icons/settings_black.svg'" alt="Paramètres" width="16" height="16"></li>
                </ul>
            </div>
        </div>
        <button class="map-open" name="menu" type="button" :class="{isDisplayed: isDisplayed}" @click="display">
            <svg width="32" height="32" viewBox="0 0 100 100">
                <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
                <path class="line line2" d="M 20,50 H 80" />
                <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
            </svg>
        </button>
        <Teleport to="#editor">
            <div class="modal-wrapper" v-if="isParametersDisplayed">
                <div class="modal">
                    <div class="modal-header">
                            <h2>Informations carte</h2>
                            <img width="24" height="24" @click="isParametersDisplayed = false" src="build/images/icons/close.svg" alt="Fermer">
                    </div>   
                    <div class="modal-body">
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
                            <span>Aucun joueur présent sur cette partie</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" @click="cancel">Annuler</button>
                        <button type="button" class="btn btn-primary" @click="submitForm">Modifier la carte</button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapActions, mapGetters } from 'vuex';
import { Connection } from '../entity/connection';
import { Map } from '../entity/map';
import { MapService } from '../services/mapService';
import { ConnectionService } from '../services/connectionService';
import basicInput from './form/basicInput.vue';

    export default defineComponent({
  components: { basicInput },
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
                /**
                 * The map parameters related to the form
                 */
                map: {} as Map,
                maps: [] as Map[],
                /**
                 * The list of connections between this world and the various users
                 */
                connections: [] as Connection[]
            }
        },
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
                'getCurrentMapId',
                'getPlayers',
                "getWorld",
                'getIsDarkTheme'
            ]),
        },
        methods: {
            ...mapActions('user', [
                'setCurrentMap'
            ]),
            ...mapActions('map', [
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
                this.isParametersDisplayed = true
                this.map = this.maps[key];
                this.connections = [];
                this.getPlayers.forEach((player: Connection) => {
                    this.connections.push(player)
                });
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
                this.mapService.updateMapPartially(this.map)
                this.isParametersDisplayed = false
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
    }

    .map-actions {
        position: absolute;
        left: calc(50% - 8px);
        bottom: 0;
    }

    .map-actions li {
        cursor: pointer;
    }

    .modal-wrapper {
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

    .modal {
        display: block;
        min-width: 256px;
        width: 800px;
        min-height: 256px;
        height: 100%;
        max-height: 480px;
        background-color: #FFFFFF;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border-bottom: solid 1px #73808C;
        background-color: #BBBFC3;
        height: 40px;
    }

    .modal-body {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: calc(100% - 88px);
        width: 100%;
        padding: 24px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 48px;
        gap: 8px;
        padding: 8px;
        border-top: solid 1px #73808C;
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

    .dark .modal-header {
        background-color: #0E1318;
    }

    .dark .modal-body, .dark .modal-footer {
        background-color: #4F5A64;
    }

</style>