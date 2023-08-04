import axios from "axios"

const state = {
    userId: 0,
    username: "",
    players: [],
    connection: {
        id: 0,
        isGameMaster: false,
        lastConnectionAt: new Date(),
        currentMap : {
            id: 0
        }
    },
    connectedUser: [],
    world: null
}

const getters = {
    isGameMaster: (state) => {
        return state.connection.isGameMaster
    },
    getUserId: (state) => {
        return state.userId
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
    }
}

const actions = {
    setUserId({commit}, userId) {
        commit('setUserId', userId)
    },
    setUserName({commit}, username) {
        commit('setUserName', username)
    },
    setPlayers({commit}, players) {
        commit('setPlayers', players)
    },
    setConnection({commit}, connection) {
        commit('setConnection', connection)
    },
    setWorld({commit}, world) {
        commit('setWorld', world)
    },
    setLastConnectionAt(lastConnectionAt) {
        commit('setLastConnectionAt', lastConnectionAt)
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
        }, 5000)
    },
    getAllConnections({commit, getters, dispatch}) {
        let lastConnectionAt = new Date(Date.parse(getters.getConnection.lastConnectionAt) - 18000)
        axios.get('/api/connections?world.id=' + getters.getWorld.id + "&lastConnectionAt[after]=" + lastConnectionAt.toJSON())
            .then(response => {
                commit('setConnectedUser', response.data['hydra:member'])
            })
        setTimeout(() => {
            dispatch("getAllConnections")
        }, 15000)
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
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}