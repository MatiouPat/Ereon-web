import { World } from "../entity/world"
import { User } from "../entity/user"
import { Personage } from "../entity/personage"
import { Connection } from "../entity/connection"
import { ConnectionService } from "../services/connectionService"
import { defineStore } from "pinia"

let connectionService = new ConnectionService();

export const useUserStore = defineStore('user', {
    state: () => ({
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
    }),
    getters: {
        isGameMaster: (state) => {
            return state.connection.isGameMaster;
        },
        getUserId: (state) => {
            return state.user.id;
        },
        getUsername: (state) => {
            return state.user.username;
        },
        getUser: (state) => {
            return state.user;
        },
        getPlayers: (state) => {
            return state.players;
        },
        getConnection: (state) => {
            return state.connection;
        },
        getWorld: (state) => {
            return state.world;
        },
        getConnectedUser: (state) => {
            return state.connectedUser;
        },
        getCurrentMapId: (state) => {
            return state.connection.currentMap.id;
        },
        getPersonages: (state) => {
            return state.personages;
        },
        getIsDarkTheme: (state) => {
            return state.isDarkTheme;
        }
    },
    actions: {
        setUser(user: User): void
        {
            this.user = user;
        },
        setPlayers(players: Connection[]): void
        {
            this.players = players;
        },
        setConnection(connection: Connection): void
        {
            this.connection = connection;
        },
        setWorld(world: World): void
        {
            this.world = world;
        },
        setLastConnectionAt(lastConnectionAt: Date): void
        {
            this.connection.lastConnectionAt = lastConnectionAt;
        },
        sendIsConnected(): void
        {
            const lastConnectionAt = new Date();
            connectionService.updateLastConnectionAt(this.getConnection.id, lastConnectionAt.toJSON());
            this.setLastConnectionAt(lastConnectionAt);
            setTimeout(() => {
                this.sendIsConnected();
            }, 20000)
        },
        findAllRecentConnections(): void
        {
            let lastConnectionAt = new Date(Date.parse(this.getConnection.lastConnectionAt) - 18000)
            connectionService.findAllRecentConnectionByWorld(this.getWorld.id, lastConnectionAt.toJSON())
                .then(connections => {
                    this.connectedUser = []
                    connections.forEach(userConnection => {
                        if(userConnection.id != this.connection.id) {
                            this.connectedUser.push({
                                username: userConnection.user.username
                            })
                        }
                    });
                })
            setTimeout(() => {
                this.findAllRecentConnections()
            }, 30000)
        },
        setCurrentMap(currentMapId: number): void
        {
            connectionService.updateCurrentMap(this.getConnection.id, currentMapId);
            this.connection.currentMap.id = currentMapId;
        },
        setPersonages(personages: Personage[]): void
        {
            this.personages = personages;
        },
        setIsDarkTheme(isDarkTheme: boolean): void
        {
            this.isDarkTheme = isDarkTheme;
        }
    }
})