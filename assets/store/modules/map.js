import axios from "axios"

const state = {
    map: {}
}

const getters = {

}

const actions = {
    setMap({commit}, map) {
        axios.get('api/maps/' + map).then((e) => {
            commit('setMap', e.data)
        })
    }
}

const mutations = {
    setMap (state, map) {
        state.map = map
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}