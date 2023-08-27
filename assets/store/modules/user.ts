import axios from "axios"
import { World } from "../../entity/world"
import { User } from "../../entity/user"
import { Personage } from "../../entity/personage"
import { Connection } from "../../entity/connection"

const state = {
    /**
     * The logged-in user id
     */
    userId: 0 as number,
    /**
     * The name of the logged-in user
     */
    username: "" as string,
    /**
     * The list of users in the world
     */
    players: [] as User[],
    /**
     * The connection between the connected user and the world
     */
    connection: {} as Connection,
    /**
     * The list of users connected to this world except the current user
     */
    connectedUser: [] as User[],
    /**
     * The user's chosen world
     */
    world: {} as World,
    /**
     * The list of characters that can be played by the user
     */
    personages: [] as Personage[]
}

const getters = {
    isGameMaster: (state) => {
        return state.connection.isGameMaster
    },
    getUserId: (state) => {
        return state.userId
    },
    getUsername: (state) => {
        return state.username
    },
    getPlayers: (state) => {
        return state.players
    },
    getConnection: (state) => {
        return state.connection
    },
    getWorld: (state) => {
        return state.world
    },
    getConnectedUser: (state) => {
        return state.connectedUser
    },
    getCurrentMapId: (state) => {
        return state.connection.currentMap.id
    },
    getPersonages: (state) => {
        return state.personages
    }
}

const actions = {
    setUserId({commit}, userId) {
        commit('setUserId', userId);
    },
    setUserName({commit}, username) {
        commit('setUserName', username);
    },
    setPlayers({commit}, players) {
        commit('setPlayers', players);
    },
    setConnection({commit}, connection) {
        commit('setConnection', connection);
    },
    setWorld({commit}, world) {
        commit('setWorld', world);
    },
    setLastConnectionAt({commit}, lastConnectionAt) {
        commit('setLastConnectionAt', lastConnectionAt);
    },
    sendIsConnected({commit, getters, dispatch}) {
        commit('setLastConnectionAt', new Date())
        axios.patch('/api/connections/' + getters.getConnection.id, {
            lastConnectionAt: getters.getConnection.lastConnectionAt.toJSON()
        }, {
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
        setTimeout(() => {
            dispatch("sendIsConnected")
        }, 20000)
    },
    getAllConnections({commit, getters, dispatch}) {
        let lastConnectionAt = new Date(Date.parse(getters.getConnection.lastConnectionAt) - 18000)
        axios.get('/api/connections?world.id=' + getters.getWorld.id + "&lastConnectionAt[after]=" + lastConnectionAt.toJSON())
            .then(response => {
                commit('setConnectedUser', response.data['hydra:member'])
            })
        setTimeout(() => {
            dispatch("getAllConnections")
        }, 30000)
    },
    setCurrentMap({commit, getters}, currentMapId) {
        commit('setCurrentMap', currentMapId)
        axios.patch('/api/connections/' + getters.getConnection.id, {
            currentMap: "/api/maps/" + currentMapId
        }, {
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    },
    downloadPersonages({commit, getters}) {
        axios.get('/api/personages?world.id=' + getters.getWorld.id + '&user.id=' + getters.getUserId)
        .then(response => {
            commit('setPersonages', response.data['hydra:member'])
        })
    }
}

const mutations = {
    setUserId(state, userId) {
        state.userId = userId
    },
    setUserName(state, username) {
        state.username = username
    },
    setPlayers(state, players) {
        state.players = players
    },
    setConnection(state, connection) {
        state.connection.id = connection.id
        state.connection.isGameMaster = connection.isGameMaster
        state.connection.lastConnectionAt = connection.lastConnectionAt
        state.connection.currentMap.id = connection.currentMap.id
    },
    setWorld(state, world) {
        state.world = world
    },
    setLastConnectionAt(state, lastConnectionAt) {
        state.connection.lastConnectionAt = lastConnectionAt
    },
    setConnectedUser(state, userConnections) {
        state.connectedUser = []
        userConnections.forEach(userConnection => {
            if(userConnection.id != state.connection.id) {
                state.connectedUser.push({
                    username: userConnection.user.username
                })
            }
        });
    },
    setCurrentMap(state, currentMapId) {
        state.connection.currentMap.id = currentMapId
    },
    setPersonages(state, personages) {
        state.personages = personages
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}