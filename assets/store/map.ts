import { Token } from "../entity/token"
import { User } from "../entity/user"
import { Map } from "../entity/map"
import { TokenService } from "../services/tokenService"
import { LightingWall } from "../entity/lightingwall"
import { LightingWallService } from "../services/lightingWallService"
import { defineStore } from "pinia"

export const useMapStore = defineStore('map', {
    state: () => ({
        /**
        * The current map
        */
        map: {} as Map,
        ratio: 1 as number,
        layer: 1 as number,
        onDrawing: false as boolean
    }),
    getters: {
        getMap: (state) => {
            return state.map
        },
        getLayer: (state) => {
            return state.layer
        },
        getOnDrawing: (state) => {
            return state.onDrawing;
        },
        getTokenById: (state) => (tokenId: number) => {
            return state.map.tokens.find((token: Token) => token.id === tokenId)
        },
        canControlledBy: (state) => (userId: number, tokenId: number) => {
            return (state.map.tokens.find((token: Token) => token.id === tokenId && token.users!.find((user: User) => user.id == userId)) != null) ? true : false
        },
        getIndexOfUser: (state) => (user: User, tokenId: number) => {
            return state.map.tokens.find((token: Token) => token.id === tokenId).users.indexOf(user)
        },
        getRatio: (state) => {
            return state.ratio
        },
        getControllableTokens: (state) => (userId: number) => {
            return state.map.tokens.filter((token: Token) => token.users!.find((user: User) => user.id == userId))
        }
    },
    actions: {
        /**
        * Define current map
        */
        async setMap(map: Map): Promise<void>
        {
            this.map = map;
        },
        /**
         * Add a token to the map
         */
        async addTokenOnMap({mercure, data}: {mercure: boolean, data: Token | number}): Promise<void>
        {
            if (!mercure && typeof data === "number") {
                let tokenService = new TokenService;
                tokenService.createToken(this.layer, this.map.id, data);
            } else {
                this.map.tokens.push(data)
            }
            
        },
        /**
         * Delete a token on the map
         */
        removeTokenOnMap({mercure, id}: {mercure: boolean, id: number}): void
        {
            let tokenService = new TokenService;
            let token = this.getTokenById(id)
            if (!mercure) {
                tokenService.deleteToken(token.id)
            }else {
                let index = this.map.tokens.findIndex((token: Token) => {
                    return token.id === id;
                })
                this.map.tokens.splice(index, 1);
            }
        },
        /**
         * Update token information on the map
         */
        updateToken(updatedToken: Token): void
        {
            let token = this.getTokenById(updatedToken.id)
            token = updatedToken;
        },
        /**
         * Push updated token information via API
         */
        async finishUpdateToken(updatedToken: Token): Promise<void>
        {
            let tokenService = new TokenService;
            let token = this.getTokenById(updatedToken.id);
            tokenService.updateTokenPartially(token);
        },
        /**
         * Update only token depth and push modification via API
         */
        changeZIndex(updatedToken: Token)
        {
            let tokenService = new TokenService;
            let token = this.getTokenById(updatedToken.id);
            token.zIndex = updatedToken.zIndex;
            tokenService.updateTokenPartially({
                "id": token.id,
                "zIndex": token.zIndex
            });
        },
        setTokenWidth: function({token, width}: {token: Token, width: number})
        {
            this.getTokenById(token.id).width = width
        },
        setTokenHeight: function({token, height}: {token: Token, height: number})
        {
            this.getTokenById(token.id).height = height
        },
        setTokenTop: function({token, topPosition}: {token: Token, topPosition: number})
        {
            this.getTokenById(token.id).topPosition = topPosition
        },
        setTokenLeft: function({token, leftPosition}: {token: Token, leftPosition: number})
        {
            this.getTokenById(token.id).leftPosition = leftPosition
        },
        setTokenZIndex: function({token, zIndex}: {token: Token, zIndex: number})
        {
            this.getTokenById(token.id).zIndex = zIndex
        },
        setTokenLayer: function({token, layer}: {token: Token, layer: number})
        {
            this.getTokenById(token.id).layer = layer
        },
        addTokenPlayer: function({token, user}: {token: Token, user: User})
        {
            this.getTokenById(token.id).users.push(user)
        },
        removeTokenPlayer: function({token, user}: {token: Token, user: User})
        {
            this.getTokenById(token.id).users.splice(user, 1)
        },
        setRatio(ratio: number)
        {
            this.ratio = ratio
        },
        setLayer(layer: number)
        {
            this.layer = layer
        },
        setOnDrawing(onDrawing: boolean): void
        {
            this.onDrawing = onDrawing
        },
        addLightingWall(lightingWall: LightingWall): void
        {
            let lightingWallService = new LightingWallService;
            lightingWallService.createLightingWall(lightingWall);
            this.map.lightingWalls.push(lightingWall);
            
        },
        deleteAllLightingWalls(): void
        {
            let lightingWallService = new LightingWallService;
            lightingWallService.deleteAllLightingWallsByMap(this.map.lightingWalls);
            this.map.lightingWalls = []
        }
    }
})