import axios from "axios"

const state = {
    /**
     * The current map
     */
    map: {
        id: null,
        name: '',
        width: null,
        height: null,
        hasDynamicLight: false
    },
    /**
     * The list of tokens present on the current map
     */
    tokens: [],
    ratio: 1
}

const getters = {
    map: (state) => {
        return state.map
    },
    tokens: (state) => {
        return state.tokens
    },
    getTokenById: (state) => (id) => {
        return state.tokens.find(token => token.id === id)
    },
    canControlledBy: (state) => (userId, tokenId) => {
        return (state.tokens.find(token => token.id === tokenId && token.users.find(user => user.id == userId)) != null) ? true : false
    },
    getIndexOfUser: (state) => (user, tokenId) => {
        return state.tokens.find(token => token.id === tokenId).users.indexOf(user)
    },
    getRatio: (state) => {
        return state.ratio
    }
}

const actions = {
    /**
     * Define current map
     * @param {*} param0 
     * @param {*} map 
     */
    setMap({commit}, map) {
        axios.get('api/maps/' + map).then((e) => {
            commit('setMap', e.data)
        })
    },
    /**
     * Add a token to the map
     * @param {*} param0 
     * @param {*} data 
     */
    addTokenOnMap({commit, getters}, data) {
        if (!data.mercure) {
            axios.post('api/tokens', {
                "width": 64,
                "height": 64,
                'topPosition': 0,
                "leftPosition": 0,
                "zIndex": 0,
                "map": "/api/maps/" + getters.map.id,
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
                    compressedImage: token.asset.compressedImage,
                    users: token.users
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
                compressedImage: data.compressedImage,
                users: data.users
            })
        }
        
    },
    /**
     * Delete a token on the map
     * @param {*} param0 
     * @param {*} data 
     */
    removeTokenOnMap({commit, getters}, data) {
        let token = getters.getTokenById(data.id)
        if (!data.mercure) {
            axios.delete('/api/tokens/' + token.id)
        }else {
            let index = state.tokens.findIndex(object => {
                return object.id === data.id;
            })
            commit('removeToken', index)
        }
    },
    /**
     * Update token information on the map
     * @param {*} param0 
     * @param {*} data 
     */
    updateToken({commit, getters}, data) {
        let token = getters.getTokenById(data.id)
        commit('updateToken', {token, data})
    },
    /**
     * Push updated token information via API
     * @param {*} param0 
     * @param {*} data 
     */
    finishUpdateToken({commit, getters}, data) {
        let token = getters.getTokenById(data.id)
        axios.patch('/api/tokens/' + token.id, {
            "width": data.width,
            "height": data.height,
            'topPosition': data.top,
            "leftPosition": data.left,
            "zIndex": data.zIndex,
            "users": data.users
        }, {
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    },
    /**
     * Update only token depth and push modification via API
     * @param {*} param0 
     * @param {*} data 
     */
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
    },
    setRatio({commit}, data) {
        commit('setRatio', data)
    }
}

const mutations = {
    setMap (state, map) {
        /*Map*/
        state.map.id = map.id
        state.map.name = map.name
        state.map.width = map.width
        state.map.height = map.height
        state.map.hasDynamicLight = map.hasDynamicLight
        /*Tokens*/
        state.tokens = []
        map.tokens.forEach(token => {
            let users = []
            token.users.forEach(user => {
                users.push({
                    id: user.id
                })
            })
            state.tokens.push({
                id: token.id,
                width: token.width,
                height: token.height,
                top: token.topPosition,
                left: token.leftPosition,
                zIndex: token.zIndex,
                image: token.asset.image,
                compressedImage: token.asset.compressedImage,
                users: users
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
        token.zIndex = data.zIndex
        token.users = data.users
    },
    setTokenWidth(state, data) {
        data.token.width = data.width
    },
    setTokenHeight(state, data) {
        data.token.height = data.height
    },
    setTokenTop(state, data) {
        data.token.top = data.top
    },
    setTokenLeft(state, data) {
        data.token.left = data.left
    },
    setTokenZIndex(state, data) {
        data.token.zIndex = data.zIndex
    },
    addTokenPlayer(state, data) {
        data.token.users.push(data.user)
    },
    removeTokenPlayer(state, data) {
        data.token.users.splice(data.user, 1)
    },
    changeZIndex(state, data) {
        token.zIndex = data.zIndex
    },
    setRatio(state, data) {
        state.ratio = data
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}