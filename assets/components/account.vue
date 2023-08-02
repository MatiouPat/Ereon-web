<template>
    <div class="header-title-box" @click="viewAccount">
        <img src="build/images/logo/icon_120.png" alt="Ereon" width="80" height="80">
        <span class="account-username">{{ user.username }}</span>
    </div>
    <ul class="account-actions" :class="{isVisible: isVisible}">
        <li><a href="/logout" style="font-weight: 700; color: red;">Se déconnecter</a></li>
    </ul>
</template>

<script>
import axios from 'axios';
import { mapActions, mapState } from 'vuex';

    export default {
        data() {
            return {
                isVisible: false
            }
        },
        props: [
            'connectedUser'
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
            }
        },
        mounted() {
            if(this.connectedUser.roles.includes("ROLE_ADMIN")) {
                this.setGameMaster(true)
            }
            this.setId(this.connectedUser.id)
            this.setUserName(this.connectedUser.username)
            this.setMap(this.connectedUser.map.id)
            axios.get('/api/people')
                .then(response => {
                    this.setPlayers(response.data['hydra:member'])
                })
        },
    }
</script>

<style scoped>
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
        display: none;
        position: absolute;
        top: 24px;
        left: 96px;
        width: 160px;
        background-color: #FFFFFF;
    }

    .account-actions.isVisible {
        display: block;
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

</style>