<template>
    <Teleport to="#content">
        <world-view v-if="onWorldCreation" @world-view:cancel="onWorldCreation = false" @worldView:createdWorld="onWorldCreation = false; this.findWorlds(this.user.id)"></world-view>
    </Teleport>
    <header v-if="isConnected" class="header">
        <nav class="navigation">
            <ul class="all-tools" v-if="isGameMaster">
                <ul class="layers">
                    <li title="Maps" @click="setLayer(1)"><div class="layer" :class="getLayer == 1 ? 'selected' : ''"><img src="build/images/icons/map.svg" width="16" height="16" alt="Maps"></div><span>Maps</span></li>
                    <li title="Tokens" @click="setLayer(2)"><div class="layer" :class="getLayer == 2 ? 'selected' : ''"><img src="build/images/icons/token.svg" width="16" height="16" alt="Tokens"></div><span>Tokens</span></li>
                    <li title="Light" @click="setLayer(3)"><div class="layer" :class="getLayer == 3 ? 'selected' : ''"><img src="build/images/icons/light.svg" width="16" height="16" alt="Light"></div><span>Light</span></li>
                </ul>
                <ul class="tools">
                    <li title="Click" @click="setOnDrawing(false)"><div class="tool" :class="!getOnDrawing ? 'selected' : ''"><img src="build/images/icons/mouse.svg" width="20" height="20" alt="Click"></div></li>
                    <li title="Light" @click="setOnDrawing(true); setLayer(3)"><div class="tool" :class="getOnDrawing ? 'selected' : ''"><img src="build/images/icons/line.svg" width="20" height="20" alt="Light"></div></li>
                    <li title="Supprimer tous les murs" @click="deleteAllLightingWalls(); emitter.emit('drawWall')"><div class="tool"><img :src="getIsDarkTheme ? '/build/images/icons/delete_white.svg' : '/build/images/icons/delete_black.svg'" width="20" height="20" alt="Supprimer tous les murs"></div></li>
                </ul>
            </ul>
            <ul v-else>

            </ul>
            <ul>
                <li title="Paramètres" @click="onParameters = true"><img :src="getIsDarkTheme ? '/build/images/icons/settings_white.svg' : '/build/images/icons/settings_black.svg'" width="20" height="20" alt="Paramètres"></li>
                <li title="Se déconnecter"><a href="/logout"><img src="build/images/icons/logout.svg" width="20" height="20" alt="Se déconnecter"></a></li>
            </ul>
        </nav>
        <div class="connected-users" v-for="connectedUser in getConnectedUser" :key="connectedUser.id">
            <div class="header-title-box">
                <img class="account-picture" src="build/images/logo/icon_120.png" alt="Ereon" width="64" height="64">
                <span class="account-username">{{ connectedUser.username }}</span>
            </div>
        </div>
        <Teleport to="#content">
            <div class="modal-wrapper" v-if="onParameters">
                <div class="modal">
                    <div class="modal-header">
                        <h2>Paramètres</h2>
                        <img @click="onParameters = false" src="build/images/icons/close.svg" alt="Fermer">
                    </div>   
                    <div class="modal-body">
                        <ul class="parameters-navigation">
                            <li :class="pageIndex == 0 ? 'is-select' : ''" @click="pageIndex = 0">Compte</li>
                            <li :class="pageIndex == 1 ? 'is-select' : ''" @click="pageIndex = 1">Audio / Graphisme</li>
                        </ul>
                        <div>
                            <div v-show="pageIndex == 0">
                                <div class="parameters-line">
                                    <basic-input ref="username" :model-value="getUser.username" :is-required="true" :label="'Nom d\'utilisateur'" @update:model-value="(modelValue) => user.username = modelValue"></basic-input>
                                    <basic-input :model-value="getUser.discordIdentifier" :label="'Identifiant Discord'" @update:model-value="(modelValue) => user.discordIdentifier = modelValue"></basic-input>
                                </div>
                                <div class="parameters-account-password">
                                    <h3>Changement de mot de passe</h3>
                                    <div class="parameters-line">
                                        <div>
                                            <basic-input ref="newPassword" :isPassword="true" :autocomplete="'new-password'" :model-value="newPassword" :label="'Nouveau mot de passe'" @update:model-value="(modelValue) => newPassword = modelValue"></basic-input>
                                            <ul class="password-requirements">
                                                <li :class="(newPasswordConstraint & 0b10000) == 0b10000 ? 'is-valid' : ''">Un caractère majuscule</li>
                                                <li :class="(newPasswordConstraint & 0b01000) == 0b01000 ? 'is-valid' : ''">Un chiffre</li>
                                                <li :class="(newPasswordConstraint & 0b00100) == 0b00100 ? 'is-valid' : ''">Un caractère spécial</li>
                                                <li :class="(newPasswordConstraint & 0b00010) == 0b00010 ? 'is-valid' : ''">8 caractères minimum</li>
                                            </ul>
                                        </div>
                                        <basic-input ref="newPasswordCopy" :isPassword="true" :autocomplete="'new-password'" :model-value="newPasswordCopy" :label="'Confirmation du nouveau mot de passe'" @update:model-value="(modelValue) => newPasswordCopy = modelValue"></basic-input>
                                    </div>
                                </div>
                            </div>
                            <div v-show="pageIndex == 1" class="parameters-account">
                                <div>
                                    <label class="form-label">Volume global</label>
                                    <div>
                                        <input type="range" min="0" v-model="globalVolume" max="1" step="0.01" @input="changeUserVolume">
                                        <span>{{ Math.round(globalVolume * 100) }} %</span>
                                    </div>
                                </div>
                                <div>
                                    <legend class="form-label">Theme</legend>
                                    <fieldset>
                                        <div>
                                            <input class="form-control" type="radio" id="light" name="Light" v-model="isDarkTheme" :value="false" @change="changeTheme">
                                            <label for="light">Light</label>
                                        </div>
                                        <div>
                                            <input class="form-control" type="radio" id="dark" name="Dark" v-model="isDarkTheme" :value="true" @change="changeTheme">
                                            <label for="dark">Dark</label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" @click="onParameters = false">Annuler</button>
                        <button type="button" class="btn btn-primary" @click="submitForm">Modifier les paramètres</button>
                    </div>
                </div>
            </div>
        </Teleport>
    </header>
    <div v-else class="worlds-page">
        <h1>Quel monde ?</h1>
        <div class="worlds">
            <div class="world-layout" v-for="world in getWorlds" :key="world.id">
                <div v-for="connection in world.connections" :key="connection.id">
                    <div class="world" v-if="connection.user.id === connectedUser.id" @click="chooseWorld(connection, world)">
                        <img class="world-image" src="build/images/logo/background.webp" alt="">
                        <div v-if="connection.isGameMaster == 0" class="role">Joueur</div>
                        <div v-else class="role">MJ</div>
                        <h2>{{ world.name }}</h2>
                    </div>
                </div>
            </div>
            <div class="world-layout" @click="createWorld">
                <div class="world world-add">
                    <span>Créer un monde</span>
                    <img :src="getIsDarkTheme ? '/build/images/icons/add_white.svg' : '/build/images/icons/add_black.svg'" width="32" height="32">
                </div>
            </div>
        </div>
        <a class="btn btn-secondary" href="/logout">Se déconnecter</a>
    </div>
</template>

<script lang="ts">
import { defineComponent, inject } from 'vue';
import { Connection } from '../entity/connection';
import { World } from '../entity/world';
import { UserParameterRepository } from '../repository/userparameterRepository';
import { LightingWallService } from '../services/lightingwallService';
import { PersonageService } from '../services/personageService';
import { ConnectionService } from '../services/connectionService';
import { MapService } from '../services/mapService';
import basicInput from './forms/inputs/basicInput.vue';
import { User } from '../entity/user';
import { UserService } from '../services/userService';
import { mapActions, mapState } from 'pinia';
import { useMapStore } from '../store/map';
import { useUserStore } from '../store/user';
import { useMusicStore } from '../store/music';
import WorldView from './worldView.vue';

    export default defineComponent({
        data() {
            return {
                emitter: inject('emitter') as any,
                connectionService: new ConnectionService as ConnectionService,
                userParameterRepository: new UserParameterRepository as UserParameterRepository,
                personageService: new PersonageService as PersonageService,
                lightingWallService: new LightingWallService as LightingWallService,
                mapService: new MapService as MapService,
                userService: new UserService as UserService,
                /**
                 * If the world has been chosen and all related variables are updated (players, map, tokens, etc.)
                 */
                isConnected: false as boolean,
                onParameters: false as boolean,
                layer: 1 as number,
                globalVolume: this.connectedUser.userParameter.globalVolume as number,
                isDarkTheme: this.connectedUser.userParameter.isDarkTheme as boolean,
                loadedParameters: 0 as number,
                pageIndex: 0 as number,
                newPassword: "" as string,
                newPasswordCopy: "" as string,
                newPasswordConstraint: 0b00000 as number,
                user: {} as User,
                onWorldCreation: false as boolean
            }
        },
        props: [
            'connectedUser'
        ],
        computed: {
            ...mapState(useUserStore, [
                'getConnectedUser',
                'getCurrentMapId',
                'getUsername',
                'isGameMaster',
                'getIsDarkTheme',
                'getUser',
                'getWorlds'
            ]),
            ...mapState(useMapStore, [
                'getLayer',
                'getOnDrawing'
            ])
        },
        components: { basicInput, WorldView },
        watch: {
            loadedParameters: {
                handler() {
                    if(this.loadedParameters >= 3) {
                        this.emitter.emit("isDownload");
                    }
                },
                flush: 'post'
            },
            newPassword: {
                handler() {
                    this.newPasswordConstraint = this.isValidPassword()
                },
                flush: 'post' 
            }
        },
        methods: {
            ...mapActions(useUserStore, [
                'setUser',
                'setPlayers',
                'setConnection',
                'setWorld',
                'sendIsConnected',
                'findAllRecentConnections',
                'setPersonages',
                'setIsDarkTheme',
                'findWorlds'
            ]),
            ...mapActions(useMapStore, [
                'setMap',
                'setLayer',
                'setOnDrawing',
                'deleteAllLightingWalls'
            ]),
            ...mapActions(useMusicStore, [
                'setUserParameter',
                'setUserVolume'
            ]),
            /**
             * Loading information after choosing a world
             * @param connection The connection between player and world
             * @param world The selected world
             */
            chooseWorld: function(connection: Connection, world: World) {
                this.mapService.findMapById(connection.currentMap.id).then(map => {
                    this.setMap(map);
                    this.loadedParameters++;
                });
                this.personageService.findPersonagesByWorldAndByUser(world.id, connection.user.id).then(personages => {
                    this.setPersonages(personages);
                    this.loadedParameters++;
                });
                this.connectionService.findPlayerByWorldAndWhereIsNotGameMaster(world.id).then(connections => {
                    this.setPlayers(connections);
                    this.loadedParameters++;
                });
                this.setConnection(connection);
                this.setWorld(world);
                this.sendIsConnected();
                this.findAllRecentConnections();
                const updateUrl = new URL(process.env.MERCURE_PUBLIC_URL!);
                updateUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/connection/' + connection.id);

                const updateEs = new EventSource(updateUrl);
                updateEs.onmessage = e => {
                    let data = JSON.parse(e.data)
                    if (data.currentMap.id !== this.getCurrentMapId) {
                        this.mapService.findMapById(data.currentMap.id).then(map => {
                            this.setMap(map);
                        })
                        this.setConnection(data)
                    }
                }
                this.isConnected = true;
            },
            changeUserVolume: function() {
                this.setUserVolume(this.globalVolume);
                this.emitter.emit("hasChangedUserVolume")
            },
            changeTheme: function() {
                this.userParameterRepository.updateTheme(this.connectedUser.userParameter.id, this.isDarkTheme);
                this.setIsDarkTheme(this.isDarkTheme);
                this.setThemeTag();
                
            },
            setThemeTag: function() {
                if(this.isDarkTheme) {
                    document.documentElement.classList.remove('light')
                    document.documentElement.classList.add('dark')
                } else {
                    document.documentElement.classList.remove('dark')
                    document.documentElement.classList.add('light')
                }
            },
            isValidPassword: function() {
                let constraints = 0b00000;
                if(new RegExp('[A-Z]').test(this.newPassword)) {
                    constraints = constraints | 0b10000
                }
                if(new RegExp('[0-9]').test(this.newPassword)) {
                    constraints = constraints | 0b01000
                }
                if(new RegExp('[#~?!:=;.@$%\^&*\/+-]').test(this.newPassword)) {
                    constraints = constraints | 0b00100
                }
                if(new RegExp('.{8,}').test(this.newPassword)) {
                    constraints = constraints | 0b00010
                }
                if(this.newPassword == this.newPasswordCopy) {
                    constraints = constraints | 0b000001
                }
                return constraints
            },
            submitForm: function() {
                let validationStatus = this.isValidPassword();
                let isError = false;
                if(this.user.username === "") {
                    (this.$refs.username as any).setError("Cette valeur ne peut pas être vide");
                    isError = true;
                }
                if(this.newPassword != "") {
                    if((validationStatus & 0b11110) != 0b11110) {
                        (this.$refs.newPassword as any).setError("Cette valeur ne respecte pas les contraintes ci-dessous");
                    isError = true;
                    }
                    else if((validationStatus & 0b00001) != 0b00001) {
                        (this.$refs.newPasswordCopy as any).setError("Cette valeur n'est pas identique");
                        isError = true;
                    }
                    else {
                        this.user.plainPassword = this.newPassword;
                    }
                }
                if(!isError) {
                    this.userService.updateUserPartially(this.user);
                    this.onParameters = false;
                }
            },
            createWorld: function() {
                this.onWorldCreation = true;
            }
        },
        mounted() {
            this.user = this.connectedUser
            this.setUser(this.user)
            this.setUserParameter(this.connectedUser.userParameter);
            this.setThemeTag();
            this.setIsDarkTheme(this.isDarkTheme);
            this.findWorlds(this.user.id);
        }
    })
</script>

<style scoped>

    .header {
        min-width: 48px;
        width: 48px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        z-index: 1;
    }

    .navigation {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        height: 100%;
    }

    .navigation .all-tools ul:not(:last-child) {
        border-bottom: solid 2px #565656;
    }

    .navigation ul {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        padding: 16px 0;

    }

    .navigation img {
        display: block;
        margin: 0 auto;
    }

    .navigation .layers span {
        display: inline-block;
        width: 100%;
        text-align: center;
        margin-top: 4px;
    }

    .navigation .layer {
        padding: 8px;
        background-color: #969696;
        border-radius: 50%;
        transition: all 25ms ease-in-out;
    }

    .navigation :where(.layer:hover, .tool:hover) {
        outline: solid 2px #565656;
        border-radius: 20%;
    }

    .navigation .layer.selected {
        background-color: #D87D40;
        outline: solid 2px #565656;
        border-radius: 20%;
    }

    .navigation .tool {
        padding: 4px;
        border-radius: 50%;
        transition: all 25ms ease-in-out;
    }

    .navigation .tool.selected {
        outline: solid 2px #565656;
        background-color: #969696;
    }

    .header-title-box {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .header-title-box img {
        border-radius: 50%;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    }

    .connected-users {
        position: absolute;
        bottom: 8px;
        left: 56px;
        display: flex;
    }

    .account-username {
        z-index: 2;
        padding: 8px;
        min-width: 96px;
        background-color: rgba(0, 0, 0, 0.8);
        color: #FFFFFF;
        text-align: center;
        font-size: .8rem;
    }

    .account-picture {
        position: absolute;
        bottom: 16px;
        left: calc(50% - 32px);
        z-index: 1;
    }

    .account-actions li {
        padding: 8px;
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

    .parameters-navigation {
        display: flex;
        justify-content: center;
        gap: 24px;
        font-size: 1.2rem;
    }

    .parameters-navigation li {
        position: relative;
        margin-bottom: 8px;
    }

    @keyframes appear {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes transformToUnderline {
        0% {
            bottom: -12px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            left: calc(50% - 4px);
        }
        100% {
            bottom: -10px;
            width: 100%;
            height: 2px;
            border-radius: 0;
            left: 0;
        }
    }

    .parameters-navigation li:hover::before {
        content: "";
        position: absolute;
        bottom: -12px;
        left: calc(50% - 4px);
        width: 8px;
        height: 8px;
        background-color: #D87D40;
        border-radius: 50%;
        animation: appear 100ms ease-out forwards, transformToUnderline 50ms ease-out forwards 75ms;
    }

    .parameters-navigation li.is-select:hover::before {
        animation: none;
    }

    .parameters-navigation li.is-select::before {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #D87D40;
        border-radius: 0;
    }

    .parameters-account, .parameters-line {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 16px;
        row-gap: 8px;
        width: 100%;
    }

    .parameters-account-password {
        padding-top: 16px;
    }

    .parameters-account-password h3 {
        font-size: 1.2rem;
    }

    .password-requirements {
        display: grid;
        grid-template-columns: 1fr 1fr;
        row-gap: 4px;
        margin-top: 8px;
    }

    .password-requirements li {
        color: #BBBFC3;
        margin-left: 1em;

    }

    .password-requirements li::before {
        content: "\2022";
        color: #BBBFC3;
        font-weight: bold;
        display: inline-block;
        width: 1em;
        margin-left: -1em;
    }

    .password-requirements li.is-valid {
        color: #F3F4F4;
    }

    .password-requirements li.is-valid::before {
        color: #D87D40;
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

    .modal-body fieldset {
        display: flex;
        gap: 16px;
    }

    .modal-body fieldset div {
        display: flex;
        gap: 4px;
    }

    .modal-body fieldset :where(input, label) {
        margin: 0
    }

    .worlds-page {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 10;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 64px;
        background-color: #FFFFFF;
        width: 100dvw;
        height: 100dvh;
        text-align: center;
    }

    h1 {
        font-size: 4rem;
        font-weight: 700;
        padding-bottom: 8px;
    }

    .worlds {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .world-layout {
        cursor: pointer;
        margin-bottom: 48px;
    }

    .world {
        position: relative;
        display: block;
        height: 200px;
        width: 200px;
        background-color: #090D11;
    }

    .world-add {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .world:hover {
        outline: solid 4px #D87D40;
        transition: all 40ms ease-in-out;
    }

    .world:hover > h2 {
        color: #D87D40;
        transition: all 40ms ease-in-out;
    }

    .world .world-image {
        display: block;
        height: 200px;
        width: 200px;
    }

    .world h2 {
        margin-top: 16px;
        font-size: 1.8rem;
        font-weight: 700;
        text-align: center;
    }

    .world .role {
        position: absolute;
        bottom: 0;
        left: 0;
        display: inline-block;
        width: 100%;
        padding: 4px;
        text-align: center;
        color: #FFFFFF;
        background-color: #090D11;
        font-weight: 700;
    }

    .form-label {
        display: block;
        max-width: 100%;
        transition: all .1s ease;
        line-height: 28px;
    }

    .worlds-page .btn {
        margin-top: 64px;
    }

    .dark .navigation {
        background-color: #1F262D;
    }

    .dark .worlds-page {
        background-color: #1F262D;
    }

    .dark .modal-header {
        background-color: #0E1318;
    }

    .dark .modal-body, .dark .modal-footer {
        background-color: #4F5A64;
    }

</style>