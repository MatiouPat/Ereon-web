const state = {
    isPlaying: false
}

const getters = {
    isPlaying: (state) => {
        return state.isPlaying
    }
}

const actions = {
    playMusic({commit}) {
        commit('playMusic')
    },
    pauseMusic({commit}) {
        commit('pauseMusic')
    }
}

const mutations = {
    playMusic(state) {
        state.isPlaying = true
    },
    pauseMusic(state) {
        state.isPlaying = false
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}