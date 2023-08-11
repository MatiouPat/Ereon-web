<template>
    <div v-if="isConnected" class="header">
        <div class="header-title-box" @click="viewAccount">
            <img class="account-picture" src="build/images/logo/icon_120.png" alt="Ereon" width="120" height="120">
            <span class="account-username">{{ user.username }}</span>
        </div>
        <ul class="account-actions" v-if="isVisible">
            <li><a href="/logout" style="font-weight: 700; color: red;">Se déconnecter</a></li>
        </ul>
        <div class="connectedUsers" v-for="connectedUser in getConnectedUser">
            <div class="header-title-box" @click="viewAccount">
                <img class="other-user-picture" src="build/images/logo/icon_120.png" alt="Ereon" width="80" height="80">
                <span class="account-username">{{ connectedUser.username }}</span>
            </div>
        </div>
    </div>
    <div v-else class="worlds-page">
        <div class="world-layout" v-if="!worlds.length">
            <h1>Vous n'avez aucun monde disponible</h1>
            <span>Veuillez contacter un administrateur</span>
        </div>
        <div class="world-layout" v-else v-for="world in worlds">
            <div v-for="connection in world.connections">
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

<script>
import axios from 'axios';
import { getTransitionRawChildren } from 'vue';
import { mapActions, mapGetters, mapState } from 'vuex';

    export default {
        data() {
            return {
                /**
                 * If the account context box is visible
                 */
                isVisible: false,
                /**
                 * If the world has been chosen and all related variables are updated (players, map, tokens, etc.)
                 */
                isConnected: false
            }
        },
        props: [
            'connectedUser',
            'worlds'
        ],
        computed: {
            ...mapState({
                user: state => state.user
            }),
            ...mapGetters('user', [
                    'getConnectedUser',
                    'getCurrentMapId'
                ]),
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
                'setMap'
            ]),
            ...mapActions('music', [
                'playMusic'
            ]),
            /**
             * Display account context box when clicked
             */
            viewAccount: function() {
                this.isVisible = !this.isVisible
                window.addEventListener('click', this.clickOutside)
            },
            /**
             * Loading information after choosing a world
             * @param {*} connection The connection between player and world
             * @param {*} world The selected world
             */
            chooseWorld: function(connection, world) {
                this.setMap(connection.currentMap.id)
                this.setUserId(connection.user.id)
                this.setUserName(connection.user.username)
                this.setConnection(connection)
                this.setWorld(world)
                this.sendIsConnected()
                this.getAllConnections()
                axios.get('/api/users?connections.isGameMaster=false&connections.world.id=' + world.id)
                    .then(response => {
                        this.setPlayers(response.data['hydra:member'])
                    })
                this.downloadPersonages()
                this.isConnected = true
                this.playMusic()
                const updateUrl = new URL(process.env.MERCURE_PUBLIC_URL);
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
            /**
             * Hide context box when clicked outside it
             * @param {*} e 
             */
            clickOutside: function(e) {
                if(!this.$el.contains(e.target)){
                    window.removeEventListener('click', this.clickOutside)
                    this.isVisible = false;
                }
            }
        }
    }
</script>

<style scoped>

    .header {
        position: absolute;
        bottom: 16px;
        left: 16px;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        gap: 16px;
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

    .account-picture {
        position: absolute;
        bottom: 16px;
        left: calc(50% - 60px);
        z-index: 1;
    }

    .account-username {
        z-index: 2;
        padding: 8px;
        min-width: 120px;
        background-color: rgba(0, 0, 0, 0.8);
        color: #FFFFFF;
        text-align: center;
    }

    .account-actions {
        position: absolute;
        bottom: 60px;
        left: 96px;
        width: 160px;
        z-index: 3;
        background-color: #FFFFFF;
    }

    .other-user-picture {
        position: absolute;
        bottom: 16px;
        left: calc(50% - 40px);
        z-index: 1;
    }

    .account-actions::after {
        content: "";
        position: absolute;
        top: 8px;
        left: -8px;
        display : inline-block;
        height : 0;
        width : 0;
        border-top : 8px solid transparent;
        border-right : 8px solid #FFFFFF;
        border-bottom : 8px solid transparent;
    }

    .account-actions li {
        padding: 8px;
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
        gap: 128px;
        background-color: #FFFFFF;
        width: 100dvw;
        height: 100dvh;
        text-align: center;
    }

    h1 {
        font-size: 2rem;
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

</style>