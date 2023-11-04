import { World } from "../../entity/world"
import { User } from "../../entity/user"
import { Personage } from "../../entity/personage"
import { Connection } from "../../entity/connection"
import { ConnectionService } from "../../services/connectionService"

let connectionService = new ConnectionService();

const state = {
    /**
     * The logged-in user
     */
    user: {} as User,
    /**
     * The list of players in the world
     */
    players: [] as Connection[],
    /**
     * The connection between the connected user and the world
     */
    connection: {} as Connection,
    /**
     * The list of users connected to this world except the current user
     */
    connectedUser: [] as User[],
    /**
     * The user's chosen world
     */
    world: {} as World,
    /**
     * The list of characters that can be played by the user
     */
    personages: [] as Personage[],
    isDarkTheme: false as boolean
}

const getters = {
    isGameMaster: (state: any) => {
        return state.connection.isGameMaster
    },
    getUserId: (state: any) => {
        return state.user.id
    },
    getUsername: (state: any) => {
        return state.user.username
    },
    getPlayers: (state: any) => {
        return state.players
    },
    getConnection: (state: any) => {
        return state.connection
    },
    getWorld: (state: any) => {
        return state.world
    },
    getConnectedUser: (state: any) => {
        return state.connectedUser
    },
    getCurrentMapId: (state: any) => {
        return state.connection.currentMap.id
    },
    getPersonages: (state: any) => {
        return state.personages
    },
    getIsDarkTheme: (state: any) => {
        return state.isDarkTheme;
    }
}

const actions = {
    setUser({commit}, user: User): void
    {
        commit('setUser', user);
    },
    setPlayers({commit}, players: Connection[]): void
    {
        commit('setPlayers', players);
    },
    setConnection({commit}, connection: Connection): void
    {
        commit('setConnection', connection);
    },
    setWorld({commit}, world: World): void
    {
        commit('setWorld', world);
    },
    setLastConnectionAt({commit}, lastConnectionAt: Date): void
    {
        commit('setLastConnectionAt', lastConnectionAt);
    },
    sendIsConnected({commit, getters, dispatch}): void
    {
        const lastConnectionAt = new Date()
        connectionService.updateLastConnectionAt(getters.getConnection.id, lastConnectionAt.toJSON());
        commit('setLastConnectionAt', lastConnectionAt)
        setTimeout(() => {
            dispatch("sendIsConnected")
        }, 20000)
    },
    findAllRecentConnections({commit, getters, dispatch}): void
    {
        let lastConnectionAt = new Date(Date.parse(getters.getConnection.lastConnectionAt) - 18000)
        connectionService.findAllRecentConnectionByWorld(getters.getWorld.id, lastConnectionAt.toJSON())
            .then(connections => {
                commit('setConnectedUser', connections)
            })
        setTimeout(() => {
            dispatch("findAllRecentConnections")
        }, 30000)
    },
    setCurrentMap({commit, getters}, currentMapId: number): void
    {
        connectionService.updateCurrentMap(getters.getConnection.id, currentMapId);
        commit('setCurrentMap', currentMapId);
    },
    setPersonages({commit}, personages: Personage[]): void
    {
        commit('setPersonages', personages);
    },
    setIsDarkTheme({commit}, isDarkTheme: boolean): void
    {
        commit('setIsDarkTheme', isDarkTheme);
    }
}

const mutations = {
    setUser(state: any, user: User) {
        state.user = user;
    },
    setPlayers(state: any, players: Connection[]) {
        state.players = players
    },
    setConnection(state: any, connection: Connection) {
        state.connection = connection
    },
    setWorld(state: any, world: World) {
        state.world = world
    },
    setLastConnectionAt(state: any, lastConnectionAt: Date) {
        state.connection.lastConnectionAt = lastConnectionAt
    },
    setConnectedUser(state: any, userConnections: Connection[]) {
        state.connectedUser = []
        userConnections.forEach(userConnection => {
            if(userConnection.id != state.connection.id) {
                state.connectedUser.push({
                    username: userConnection.user.username
                })
            }
        });
    },
    setCurrentMap(state: any, currentMapId: number) {
        state.connection.currentMap.id = currentMapId
    },
    setPersonages(state: any, personages: Personage[]) {
        state.personages = personages
    },
    setIsDarkTheme(state: any, isDarkTheme: boolean) {
        state.isDarkTheme = isDarkTheme;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}