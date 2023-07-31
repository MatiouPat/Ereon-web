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
    addTokenOnMap({commit, getters}, data) {
        if (!data.mercure) {
            axios.post('api/tokens', {
                "width": 64,
                "height": 64,
                'topPosition': 0,
                "leftPosition": 0,
                "zIndex": 0,
                "maps": [
                    "/api/maps/" + getters.map.id
                ],
                "asset": "/api/assets/" + data.id
            }).then(response => {
                let token = response.data
                commit('addToken', {
                    id: token.id,
                    width: token.width,
                    height: token.height,
                    top: token.topPosition,
                    left: token.leftPosition,
                    zIndex: token.zIndex,
                    image: token.asset.image,
                    compressedImage: token.asset.compressedImage
                })
            })
        } else {
            commit('addToken', {
                id: data.id,
                width: data.width,
                height: data.height,
                top: data.top,
                left: data.left,
                zIndex: data.zIndex,
                image: data.image,
                compressedImage: data.compressedImage
            })
        }
        
    },
    removeTokenOnMap({commit, getters}, data) {
        let token = getters.getTokenById(data.id)
        if (!data.mercure) {
            axios.delete('/api/tokens/' + token.id)
        }
        let index = state.tokens.findIndex(object => {
            return object.id === data.id;
        })
        commit('removeToken', index)
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
    },
    changeZIndex({commit, getters}, data) {
        let token = getters.getTokenById(data.id)
        commit('changeZIndex', {token, data}),
        axios.patch('/api/tokens/' + token.id, {
            "zIndex": data.zIndex
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
                zIndex: token.zIndex,
                image: token.asset.image,
                compressedImage: token.asset.compressedImage
            })
        });
    },
    addToken(state, token) {
        state.tokens.push(token)
    },
    removeToken(state, tokenId) {
        state.tokens.splice(tokenId, 1)
    },
    updateToken(state, {token, data}) {
        token.width = data.width
        token.height = data.height
        token.top = data.top
        token.left = data.left
    },
    changeZIndex(state, {token, data}) {
        token.zIndex = data.zIndex
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}