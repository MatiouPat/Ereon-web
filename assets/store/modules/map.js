import axios from "axios"
import Vue from 'vue'

const state = {
    map: {
        id: null,
        name: '',
        width: null,
        height: null
    },
    tokens: []
}

const getters = {
    map: (state) => {
        return state.map
    },
    getTokenById: (state) => (id) => {
        return state.tokens.find(token => token.id === id)
    }
}

const actions = {
    setMap({commit}, map) {
        axios.get('api/maps/' + map).then((e) => {
            commit('setMap', e.data)
        })
    },
    updateToken({commit, getters}, data) {
        let token = getters.getTokenById(data.id)
        commit('updateToken', {token, data})
    },
    finishUpdateToken({commit, getters}, data) {
        let token = getters.getTokenById(data.id)
        axios.patch('/api/tokens/' + token.id, {
            "width": data.width,
            "height": data.height,
            'topPosition': data.top,
            "leftPosition": data.left
        }, {
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    }
}

const mutations = {
    setMap (state, map) {
        /*Map*/
        state.map.id = map.id
        state.map.name = map.name
        state.map.width = map.width
        state.map.height = map.height
        /*Tokens*/
        map.tokens.forEach(token => {
            state.tokens.push({
                id: token.id,
                width: token.width,
                height: token.height,
                top: token.topPosition,
                left: token.leftPosition,
                image: token.asset.image,
                compressedImage: token.asset.compressedImage
            })
        });
    },
    updateToken(state, {token, data}) {
        token.width = data.width
        token.height = data.height
        token.top = data.top
        token.left = data.left
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}