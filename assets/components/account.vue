<template>
    <div v-if="isConnected" class="header">
        <div class="header-title-box" @click="viewAccount">
            <img src="build/images/logo/icon_120.png" alt="Ereon" width="80" height="80">
            <span class="account-username">{{ user.username }}</span>
        </div>
        <ul class="account-actions" v-if="isVisible">
            <li><a href="/logout" style="font-weight: 700; color: red;">Se déconnecter</a></li>
        </ul>
    </div>
    <div v-else class="worlds-page">
        <div class="world-layout" v-if="!worlds.length">
            <h1>Vous n'avez aucun monde disponible</h1>
            <span>Veuillez contacter un administrateur</span>
        </div>
        <div class="world-layout" v-else v-for="world in worlds">
            <div class="world" @click="chooseWorld(world)">
                    <picture>
                        <source>
                        <img src="build/images/logo/icon_180.png" alt="">
                    </picture>
                    <div v-if="world.connections[0].isGameMaster == 0" class="role">Joueur</div>
                    <div v-else class="role">MJ</div>
                    <h2>{{ world.name }}</h2>
                </div>
        </div>
        <a class="btn" href="/logout">Se déconnecter</a>
    </div>
</template>

<script>
import axios from 'axios';
import { mapActions, mapState } from 'vuex';

    export default {
        data() {
            return {
                isVisible: false,
                isConnected: false
            }
        },
        props: [
            'connectedUser',
            'worlds'
        ],
        computed: mapState({
            user: state => state.user
        }),
        methods: {
            ...mapActions('user', [
                'setId',
                'setGameMaster',
                'setUserName',
                'setPlayers'
            ]),
            ...mapActions('map', [
                'setMap'
            ]),
            viewAccount: function() {
                this.isVisible = !this.isVisible
            },
            chooseWorld: function(world) {
                this.setMap(world.connections[0].currentMap.id)
                this.setGameMaster(world.connections[0].isGameMaster)
                this.setId(world.connections[0].user.id)
                this.setUserName(world.connections[0].user.username)
                axios.get('/api/users?connections.isGameMaster=false&connections.world.id=' + world.id)
                    .then(response => {
                        this.setPlayers(response.data['hydra:member'])
                    })
                this.isConnected = true
            }
        }
    }
</script>

<style scoped>

    .header {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 2;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        border-radius: 50%;
        justify-content: space-between;
    }

    .header-title-box {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .header-title-box img {
        border-radius: 50%;
    }

    .account-username {
        position: absolute;
        bottom: -16px;
        left: 0;
        padding: 8px;
        background-color: rgba(0, 0, 0, 0.8);
        color: #FFFFFF;
    }

    .account-actions {
        position: absolute;
        top: 24px;
        left: 96px;
        width: 160px;
        background-color: #FFFFFF;
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
        background-color: red;
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