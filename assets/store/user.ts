import { World } from "../entity/world"
import { User } from "../entity/user"
import { Personage } from "../entity/personage"
import { Connection } from "../entity/connection"
import { ConnectionService } from "../services/connectionService"
import { defineStore } from "pinia"
import { WorldService } from "../services/worldService";

let connectionService = new ConnectionService();
let worldService = new WorldService();

export const useUserStore = defineStore('user', {
    state: () => ({
        /**
         * The logged-in user
         */
        connectedUser: {} as User,
        /**
         * The connection between the connected user and the world
         */
        connection: {} as Connection,
        /**
         * The list of users connected to this world except the current user
         */
        connectedPlayer: [] as User[],
        /**
         * The list of characters that can be played by the user
         */
        personages: [] as Personage[],
        isDarkTheme: false as boolean,
        worlds: [] as World[]
    }),
    getters: {
        isGameMaster: (state) => {
            return state.connection.isGameMaster;
        },
        getUserId: (state) => {
            return state.connectedUser.id;
        },
        getUsername: (state) => {
            return state.connectedUser.username;
        },
        getUser: (state) => {
            return state.connectedUser;
        },
        getConnection: (state) => {
            return state.connection;
        },
        getWorld: () => {
            return {};
        },
        getConnectedPlayer: (state) => {
            return state.connectedPlayer;
        },
        getCurrentMapId: (state) => {
            return state.connection.currentMap.id;
        },
        getPersonages: (state) => {
            return state.personages;
        },
        getIsDarkTheme: (state) => {
            return state.isDarkTheme;
        },
        getWorlds: (state) => {
            return state.worlds;
        }
    },
    actions: {
        setUser(user: User): void
        {
            this.connectedUser = user;
        },
        setPlayers(players: Connection[]): void
        {
            this.players = players;
        },
        setConnection(connection: Connection): void
        {
            this.connection = connection;
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
            /*setTimeout(() => {
                this.sendIsConnected();
            }, 20000)*/
        },
        findAllRecentConnections(worldId: number): void
        {
            let lastConnectionAt = new Date(Date.parse(this.getConnection.lastConnectionAt) - 18000)
            connectionService.findAllRecentConnectionByWorld(worldId, lastConnectionAt.toJSON())
                .then(connections => {
                    this.connectedPlayer = []
                    connections.forEach(userConnection => {
                        if(userConnection.id != this.connection.id) {
                            this.connectedPlayer.push({
                                username: userConnection.user.username
                            })
                        }
                    });
                })
            /*setTimeout(() => {
                this.findAllRecentConnections()
            }, 30000)*/
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
        },
        findWorlds(userId: number): void
        {
            worldService.findWorldByUser(userId).then(worlds => {
                this.worlds = worlds;
            })
        },
        addWorld(world: World): void
        {
            this.worlds.push(world);
        }
    }
})