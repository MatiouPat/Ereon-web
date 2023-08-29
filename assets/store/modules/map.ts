import axios from "axios"
import { Token } from "../../entity/token"
import { User } from "../../entity/user"
import { Map } from "../../entity/map"

const state = {
    /**
     * The current map
     */
    map: {
        width: 0,
        height: 0
    } as Map,
    ratio: 1 as number,
    layer: 1 as number
}

const getters = {
    map: (state: any) => {
        return state.map
    },
    getLayer: (state: any) => {
        return state.layer
    },
    getTokenById: (state: any) => (tokenId: number) => {
        return state.map.tokens.find((token: Token) => token.id === tokenId)
    },
    canControlledBy: (state: any) => (userId: number, tokenId: number) => {
        return (state.map.tokens.find((token: Token) => token.id === tokenId && token.users?.find((user: User) => user.id == userId)) != null) ? true : false
    },
    getIndexOfUser: (state: any) => (user: User, tokenId: number) => {
        return state.map.tokens.find((token: Token) => token.id === tokenId).users.indexOf(user)
    },
    getRatio: (state: any) => {
        return state.ratio
    }
}

const actions = {
    /**
     * Define current map
     */
    setMap: async function({commit}, map: Map): Promise<void>
    {
        axios.get('api/maps/' + map).then((e) => {
            commit('setMap', e.data)
        })
    },
    /**
     * Add a token to the map
     */
    addTokenOnMap: async function({commit, getters}, {mercure, data}: {mercure: boolean, data: Token | string}): Promise<void>
    {
        if (!mercure) {
            axios.post('api/tokens', {
                "width": 64,
                "height": 64,
                'topPosition': 0,
                "leftPosition": 0,
                "zIndex": 0,
                "layer": getters.getLayer,
                "map": getters.map['@id'],
                "asset": "/api/assets/" + data
            })
        } else {
            commit('addToken', data)
        }
        
    },
    /**
     * Delete a token on the map
     */
    removeTokenOnMap: function({commit, getters}, {mercure, id}: {mercure: boolean, id: number})
    {
        let token = getters.getTokenById(id)
        if (!mercure) {
            axios.delete('/api/tokens/' + token.id)
        }else {
            console.log(id)
            let index = state.map.tokens.findIndex((token: Token) => {
                return token.id === id;
            })
            commit('removeToken', index)
        }
    },
    /**
     * Update token information on the map
     */
    updateToken: function({commit, getters}, updatedToken: Token): void
    {
        let token = getters.getTokenById(updatedToken.id)
        commit('updateToken', {token, updatedToken})
    },
    /**
     * Push updated token information via API
     */
    finishUpdateToken: async function({getters}, updatedToken: Token): Promise<void>
    {
        let token = getters.getTokenById(updatedToken.id);
        let users = [] as string[];
        updatedToken.users.forEach((user: User) => {
            users.push(user["@id"]!);
        })
        axios.patch('/api/tokens/' + token.id, {
            "width": updatedToken.width,
            "height": updatedToken.height,
            'topPosition': updatedToken.topPosition,
            "leftPosition": updatedToken.leftPosition,
            "zIndex": updatedToken.zIndex,
            "users": users
        }, {
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    },
    /**
     * Update only token depth and push modification via API
     */
    changeZIndex: function({commit, getters}, updatedToken: Token)
    {
        let token = getters.getTokenById(updatedToken.id)
        let zIndex = updatedToken.zIndex
        commit('setTokenZIndex', {token, zIndex}),
        axios.patch('/api/tokens/' + token.id, {
            "zIndex": zIndex
        }, {
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        })
    },
    setRatio: function({commit}, data)
    {
        commit('setRatio', data)
    },
    setLayer: function({commit}, layer: number)
    {
        commit('setLayer', layer)
    }
}

const mutations = {
    setMap: function(state: any, map: Map)
    {
        state.map = map
    },
    addToken: function(state: any, token: Token)
    {
        state.map.tokens.push(token)
    },
    removeToken: function(state: any, tokenId: number)
    {
        state.map.tokens.splice(tokenId, 1)
    },
    updateToken: function(state: any, {token, updatedToken}: {token: Token, updatedToken: Token})
    {
        token.width = updatedToken.width
        token.height = updatedToken.height
        token.topPosition = updatedToken.topPosition
        token.leftPosition = updatedToken.leftPosition
        token.zIndex = updatedToken.zIndex
        token.users = updatedToken.users
    },
    setTokenWidth: function(state: any, {token, width}: {token: Token, width: number})
    {
        token.width = width
    },
    setTokenHeight: function(state: any, {token, height}: {token: Token, height: number})
    {
        token.height = height
    },
    setTokenTop: function(state: any, {token, topPosition}: {token: Token, topPosition: number})
    {
        token.topPosition = topPosition
    },
    setTokenLeft: function(state: any, {token, leftPosition}: {token: Token, leftPosition: number})
    {
        token.leftPosition = leftPosition
    },
    setTokenZIndex: function(state: any, {token, zIndex}: {token: Token, zIndex: number})
    {
        token.zIndex = zIndex
    },
    addTokenPlayer: function(state: any, {token, user}: {token: Token, user: User})
    {
        token.users.push(user)
    },
    removeTokenPlayer: function(state: any, {token, user}: {token: Token, user: User})
    {
        token.users.splice(user, 1)
    },
    setRatio: function(state: any, data)
    {
        state.ratio = data
    },
    setLayer: function(state: any, layer: number)
    {
        state.layer = layer;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}