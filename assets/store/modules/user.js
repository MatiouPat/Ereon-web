
const state = {
    id: 0,
    username: "",
    isGameMaster: false,
    players: []
}

const getters = {
    isGameMaster: (state) => {
        return state.isGameMaster
    },
    getUserId: (state) => {
        return state.id
    },
    getPlayers: (state) => {
        return state.players
    }
}

const actions = {
    setId({commit}, id) {
        commit('setId', id)
    },
    setUserName({commit}, username) {
        commit('setUserName', username)
    },
    setGameMaster({commit}, isGameMaster) {
        commit('setGameMaster', isGameMaster)
    },
    setPlayers({commit}, players) {
        commit('setPlayers', players)
    }
}

const mutations = {
    setId(state, id) {
        state.id = id
    },
    setUserName(state, username) {
        state.username = username
    },
    setGameMaster(state, isGameMaster) {
        state.isGameMaster = isGameMaster
    },
    setPlayers(state, players) {
        state.players = players
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}