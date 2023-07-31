
const state = {
    username: "",
    isGameMaster: false
}

const getters = {
    isGameMaster: (state) => {
        return state.isGameMaster
    }
}

const actions = {
    setUserName({commit}, username) {
        commit('setUserName', username)
    },
    setGameMaster({commit}, isGameMaster) {
        commit('setGameMaster', isGameMaster)
    }
}

const mutations = {
    setUserName(state, username) {
        state.username = username
    },
    setGameMaster(state, isGameMaster) {
        state.isGameMaster = isGameMaster
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}