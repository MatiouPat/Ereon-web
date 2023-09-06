<template>
    <div class="map-box" v-if="isGameMaster" :class="{isDisplayed: isDisplayed}">
        <div class="maps">
            <div class="map" @click="chooseMap(map.id)" v-for="map in maps" :key="map.id" :style="map.id == getCurrentMapId ? 'border: solid 4px #D68836' : ''">
                <span>{{ map.name }}</span>
                <ul class="map-actions">
                    <li><img @click.stop="showMapParameter(map.id)" src="build/images/settings.svg" alt="Paramètres" width="16" height="16"></li>
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
            <div v-if="isParametersDisplayed" class="modal-wrapper">
                <div class="modal-box">
                    <form>
                        <div class="form-part">
                            <h3>Positionnement</h3>
                            <div class="row">
                                <div class="field">
                                    <label>Name</label>
                                    <input v-model="map.name" type="text">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <label>Dynamic light</label>
                                    <input v-model="map.hasDynamicLight" type="checkbox">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <label>Width</label>
                                    <input v-model="map.width" type="number">
                                </div>
                                <div class="field">
                                    <label>Height</label>
                                    <input v-model="map.height" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="form-part" v-if="connections.length">
                            <h3>Sur map</h3>
                            <div v-for="connection in connections" :key="connection.id">
                                <label>{{ connection.username }}</label>
                                <input type="checkbox" v-model="connection.checked" :checked="connection.checked">
                            </div>
                        </div>
                        <div class="form-part" v-else >
                            <h3>Sur map</h3>
                            <span>Aucun joueur présent sur cette partie</span>
                        </div>
                        <button type="button" class="btn" @click="submitForm">Valider</button>
                    </form>
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
import { ConnectionRepository } from '../repository/connectionRepository';
import { MapRepository } from '../repository/mapRepository';

    export default defineComponent({
        data() {
            return {
                mapRepository: new MapRepository as MapRepository,
                connectionRepository: new ConnectionRepository as ConnectionRepository,
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
                connections: [] as {
                    id: number
                    username: string
                    checked: boolean 
                }[]
            }
        },
        computed: {
            ...mapGetters('user', [
                'isGameMaster',
                'getCurrentMapId',
                'getPlayers',
                "getWorld"
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
                this.setMap(mapId)
                this.setCurrentMap(mapId)
                this.isDisplayed = false;
            },
            /**
             * Display map parameters by calling the API
             * @param {*} mapId 
             */
            showMapParameter: function (mapId: number) {
                this.isParametersDisplayed = true
                this.mapRepository?.findMapById(mapId).then(res => {
                    this.map = res
                });
                this.connectionRepository.findConnectionByWorld(this.getWorld.id).then(res => {
                    this.connections = []
                    res.forEach((connection: Connection) => {
                        this.connections.push({
                            id: connection.id,
                            username: connection.user.username,
                            checked: this.map?.id == connection.currentMap.id
                        })
                    });
                })
            },
            /**
             * Change map settings after form submission
             */
            submitForm: function() {
                let connections: string[] = []
                this.connections.forEach(connection => {
                    if(connection.checked) {
                        connections.push('/api/connections/' + connection.id)
                    }
                });
                this.mapRepository.updateMapPartially(this.map, connections)
                this.isParametersDisplayed = false
                this.isDisplayed = false;
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
            this.mapRepository.findAllMaps().then(res => {
                this.maps = res
            })
        }
    })
</script>

<style scoped>

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
        background-color: #D68836;
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
        background-color: #565656;
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
        z-index: 10;
        display: flex;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.75);
    }

    .modal-box {
        display: block;
        width: 400px;
        margin: auto;
        padding: 16px;
        background-color: #FFF;
    }

    .dark .map-box {
        background-color: #1c1b22;
    }


</style>