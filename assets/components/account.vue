<template>
    <header v-if="isConnected" class="header">
        <nav class="navigation">
            <ul class="layers" v-if="isGameMaster">
                <li title="Maps" @click="setLayer(1)"><div class="layer" :class="getLayer == 1 ? 'selected' : ''"><img src="build/images/icons/map.svg" width="16" height="16" alt="Maps"></div><span>Maps</span></li>
                <li title="Tokens" @click="setLayer(2)"><div class="layer" :class="getLayer == 2 ? 'selected' : ''"><img src="build/images/icons/token.svg" width="16" height="16" alt="Tokens"></div><span>Tokens</span></li>
            </ul>
            <ul v-else>

            </ul>
            <ul>
                <li title="Paramètres" @click="onParameters = true"><img src="build/images/icons/settings.svg" width="24" height="24" alt="Paramètres"></li>
                <li title="Se déconnecter"><a href="/logout"><img src="build/images/icons/logout.svg" width="24" height="24" alt="Se déconnecter"></a></li>
            </ul>
        </nav>
        <div class="connected-users" v-for="connectedUser in getConnectedUser" :key="connectedUser.id">
            <div class="header-title-box">
                <img class="account-picture" src="build/images/logo/icon_120.png" alt="Ereon" width="64" height="64">
                <span class="account-username">{{ connectedUser.username }}</span>
            </div>
        </div>
        <Teleport to="#content">
            <div class="parameters-wrapper" v-if="onParameters">
                <div class="parameters">
                    <div class="parameters-header">
                        <h2>Paramètres</h2>
                        <img @click="onParameters = false" src="build/images/icons/close.svg" alt="Fermer">
                    </div>
                    <div class="parameters-body">
                        <div>
                            <label class="form-label">Volume global</label>
                            <input class="form-control" type="range" min="0" v-model="globalVolume" max="1" step="0.01" @input="changeUserVolume">
                        </div>
                        <div>
                            <legend class="form-label">Theme</legend>
                            <fieldset>
                                <input class="form-control" type="radio" id="light" name="Light" v-model="isDarkTheme" :value="false" @change="changeTheme">
                                <label for="light">Light</label>
                                <input class="form-control" type="radio" id="dark" name="Dark" v-model="isDarkTheme" :value="true" @change="changeTheme">
                                <label for="dark">Dark</label>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </header>
    <div v-else class="worlds-page">
        <h1>Quel monde ?</h1>
        <div class="world-layout" v-if="!worlds.length">
            <h1>Vous n'avez aucun monde disponible</h1>
            <span>Veuillez contacter un administrateur</span>
        </div>
        <div class="world-layout" v-else v-for="world in worlds" :key="world.id">
            <div v-for="connection in world.connections" :key="connection.id">
                <div class="world" v-if="connection.user.id === connectedUser.id" @click="chooseWorld(connection, world)">
                    <picture>
                        <source>
                        <img src="build/images/logo/icon_180.png" alt="">
                    </picture>
                    <div v-if="connection.isGameMaster == 0" class="role">Joueur</div>
                    <div v-else class="role">MJ</div>
                    <h2>{{ world.name }}</h2>
                </div>
            </div>
        </div>
        <a class="btn" href="/logout">Se déconnecter</a>
    </div>
</template>

<script lang="ts">
import { defineComponent, inject } from 'vue';
import { mapActions, mapGetters } from 'vuex';
import { Connection } from '../entity/connection';
import { World } from '../entity/world';
import { UserParameterRepository } from '../repository/userparameterRepository';
import { UserRepository } from '../repository/userRepository';

    export default defineComponent({
        data() {
            return {
                emitter: inject('emitter') as any,
                userRepository: new UserRepository as UserRepository,
                userParameterRepository: new UserParameterRepository as UserParameterRepository,
                /**
                 * If the world has been chosen and all related variables are updated (players, map, tokens, etc.)
                 */
                isConnected: false as boolean,
                onParameters: false as boolean,
                layer: 1 as number,
                globalVolume: this.connectedUser.userParameter.globalVolume as number,
                isDarkTheme: this.connectedUser.userParameter.isDarkTheme as boolean
            }
        },
        props: [
            'connectedUser',
            'worlds'
        ],
        computed: {
            ...mapGetters('user', [
                'getConnectedUser',
                'getCurrentMapId',
                'getUsername',
                'isGameMaster',
            ]),
            ...mapGetters('map', [
                'getLayer',
            ])
        },
        methods: {
            ...mapActions('user', [
                'setUserId',
                'setUserName',
                'setPlayers',
                'setConnection',
                'setWorld',
                'sendIsConnected',
                'getAllConnections',
                'downloadPersonages'
            ]),
            ...mapActions('map', [
                'setMap',
                'setLayer'
            ]),
            ...mapActions('music', [
                'setUserParameter',
                'setUserVolume'
            ]),
            /**
             * Loading information after choosing a world
             * @param connection The connection between player and world
             * @param world The selected world
             */
            chooseWorld: function(connection: Connection, world: World) {
                this.setMap(connection.currentMap.id)
                this.setUserId(connection.user.id)
                this.setUserName(connection.user.username)
                this.setConnection(connection)
                this.setWorld(world)
                this.sendIsConnected()
                this.getAllConnections()
                this.userRepository.findUserByWorldAndWhereIsNotGameMaster(world.id).then(res => {
                    this.setPlayers(res)
                    this.emitter.emit("isDownload")
                })
                this.downloadPersonages()
                this.isConnected = true
                const updateUrl = new URL(process.env.MERCURE_PUBLIC_URL!);
                updateUrl.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/connection/' + connection.id);

                const updateEs = new EventSource(updateUrl);
                updateEs.onmessage = e => {
                    let data = JSON.parse(e.data)
                    if (data.currentMap.id !== this.getCurrentMapId) {
                        this.setMap(data.currentMap.id)
                        this.setConnection(data)
                    }
                }
            },
            changeUserVolume: function() {
                this.setUserVolume(this.globalVolume);
                this.emitter.emit("hasChangedUserVolume")
            },
            changeTheme: function() {
                this.userParameterRepository.updateTheme(this.connectedUser.id, this.isDarkTheme)
                this.setThemeTag()
            },
            setThemeTag: function() {
                if(this.isDarkTheme) {
                    document.documentElement.classList.remove('light')
                    document.documentElement.classList.add('dark')
                } else {
                    document.documentElement.classList.remove('dark')
                    document.documentElement.classList.add('light')
                }
            }
        },
        mounted() {
            this.setUserParameter(this.connectedUser.userParameter);
            this.setThemeTag();
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

    .navigation .layer:hover {
        outline: solid 2px #565656;
        border-radius: 20%;
    }

    .navigation .layer.selected {
        background-color: #D68836;
        outline: solid 2px #565656;
        border-radius: 20%;
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
        min-height: 256px;
    }

    .parameters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border-bottom: solid 1px #565656;
        background-color: #FFFFFF;
    }

    .parameters-body {
        padding: 16px;
        background-color: #FFFFFF;
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

    .world {
        position: relative;
        display: block;
        width: 10dvw;
        height: 10dvw;
        max-height: 200px;
        max-width: 200px;
    }

    .world:hover > picture {
        outline: solid 4px #D68836;
        transition: all 40ms ease-in-out;
    }

    .world:hover > h2 {
        color: #D68836;
        transition: all .2s ease-in-out;
    }

    .world picture {
        display: block;
        width: 10dvw;
        height: 10dvw;
        max-height: 200px;
        max-width: 200px;
        background-color: #333333;
    }

    .world img {
        display: block;
        width: 10dvw;
        height: 10dvw;
        max-height: 200px;
        max-width: 200px;
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
        background-color: rgba(0, 0, 0, 0.5);
        font-weight: 700;
    }

    .worlds-page .btn {
        margin-top: 64px;
    }

    .dark .navigation {
        background-color: #1c1b22;
    }

    .dark .worlds-page {
        background-color: #1c1b22;
    }

    .dark .parameters-header {
        background-color: #1c1b22;
    }

    .dark .parameters-body {
        background-color: #2b2a33;
    }

</style>