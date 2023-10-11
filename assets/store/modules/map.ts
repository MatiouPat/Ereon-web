import { Token } from "../../entity/token"
import { User } from "../../entity/user"
import { Map } from "../../entity/map"
import { TokenService } from "../../services/tokenService"
import { LightingWall } from "../../entity/lightingwall"
import { LightingWallService } from "../../services/lightingwallService"

const state = {
    /**
     * The current map
     */
    map: {
        width: 0,
        height: 0
    } as Map,
    ratio: 1 as number,
    layer: 1 as number,
    onDrawing: false as boolean
}

const getters = {
    map: (state: any) => {
        return state.map
    },
    getLayer: (state: any) => {
        return state.layer
    },
    getOnDrawing: (state: any) => {
        return state.onDrawing;
    },
    getTokenById: (state: any) => (tokenId: number) => {
        return state.map.tokens.find((token: Token) => token.id === tokenId)
    },
    canControlledBy: (state: any) => (userId: number, tokenId: number) => {
        return (state.map.tokens.find((token: Token) => token.id === tokenId && token.users!.find((user: User) => user.id == userId)) != null) ? true : false
    },
    getIndexOfUser: (state: any) => (user: User, tokenId: number) => {
        return state.map.tokens.find((token: Token) => token.id === tokenId).users.indexOf(user)
    },
    getRatio: (state: any) => {
        return state.ratio
    },
    getControllableTokens: (state: any) => (userId: number) => {
        return state.map.tokens.filter((token: Token) => token.users!.find((user: User) => user.id == userId))
    }
}

const actions = {
    /**
     * Define current map
     */
    setMap: async function({commit}, map: Map): Promise<void>
    {
        commit('setMap', map);
    },
    /**
     * Add a token to the map
     */
    addTokenOnMap: async function({commit, getters}, {mercure, data}: {mercure: boolean, data: Token | number}): Promise<void>
    {
        if (!mercure && typeof data === "number") {
            let tokenService = new TokenService;
            tokenService.createToken(getters.getLayer, getters.map.id, data);
        } else {
            commit('addToken', data)
        }
        
    },
    /**
     * Delete a token on the map
     */
    removeTokenOnMap: function({commit, getters}, {mercure, id}: {mercure: boolean, id: number})
    {
        let tokenService = new TokenService;
        let token = getters.getTokenById(id)
        if (!mercure) {
            tokenService.deleteToken(token.id)
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
        let tokenService = new TokenService;
        let token = getters.getTokenById(updatedToken.id);
        tokenService.updateTokenPartially(token);
    },
    /**
     * Update only token depth and push modification via API
     */
    changeZIndex: function({commit, getters}, updatedToken: Token)
    {
        
        let tokenService = new TokenService;
        let token = getters.getTokenById(updatedToken.id);
        let zIndex = updatedToken.zIndex
        commit('setTokenZIndex', {token, zIndex}),
        tokenService.updateTokenPartially({
            "id": token.id,
            "zIndex": token.zIndex
        });
    },
    setRatio: function({commit}, data)
    {
        commit('setRatio', data)
    },
    setLayer: function({commit}, layer: number)
    {
        commit('setLayer', layer)
    },
    setOnDrawing: function({commit}, onDrawing: boolean): void
    {
        commit('setOnDrawing', onDrawing);
    },
    addLightingWall: function({commit}, lightingWall: LightingWall): void
    {
        let lightingWallService = new LightingWallService;
        lightingWallService.createLightingWall(lightingWall);
        commit('addLightingWall', lightingWall);
        
    },
    deleteAllLightingWalls: function({commit, getters}): void
    {
        let lightingWallService = new LightingWallService;
        lightingWallService.deleteAllLightingWallsByMap(getters.map.lightingWalls);
        commit('deleteAllLightingWalls');
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
    setTokenLayer: function(state: any, {token, layer}: {token: Token, layer: number})
    {
        token.layer = layer
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
    },
    setOnDrawing: function(state: any, onDrawing: number)
    {
        state.onDrawing = onDrawing;
    },
    addLightingWall: function(state:any, lightingWall: LightingWall)
    {
        state.map.lightingWalls.push(lightingWall);
    },
    deleteAllLightingWalls: function(state:any)
    {
        state.map.lightingWalls = []
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}