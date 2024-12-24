import {defineStore} from "pinia";
import {World} from "../entity/world";
import {Connection} from "../entity/connection";
import { Map } from "../entity/map";

export const useWorldStore = defineStore('world', {
    state: () => ({
        /**
         * The user's chosen world
         */
        world: {} as World,
        playerConnections: [] as Connection[]
    }),
    getters: {
        getWorld: (state) => {
            return state.world;
        },
        getWorldId: (state) => {
            return state.world.id;
        },
        getMaps: (state) => {
            return state.world.maps;
        },
        getPlayerConnectionsInWorld: (state) => {
            return state.playerConnections;
        },
        getDices: (state) => {
            return state.world.dices;
        },
        getPersonages: (state) => {
            return state.world.personages;
        },
        getWorldAttributes: (state) => {
            return state.world.attributes;
        },
        getWorldPoints: (state) => {
            return state.world.points;
        }
    },
    actions: {
        setWorld(world: World): void
        {
            this.world = world;
            this.playerConnections = world.connections?.filter((connection) => connection.isGameMaster === false);
        },
        moveConnectionFromMap(connection: Connection, map: Map): void
        {
            let oldMap = connection.currentMap;
            connection.currentMap = map;

            let mapConnection = map.connections.find(mapConnection => {
                if (connection.id == mapConnection.id) {
                    return mapConnection
                }
                return undefined;
            })

            if(mapConnection) {
                mapConnection.currentMap = "/api/maps/" + map.id;
            }else {
                map.connections.push(connection)
            }

            oldMap.connections.forEach((mapConnection, index: number) => {
                if (mapConnection === "/api/connections/" + connection.id) {
                    oldMap.connections[index] = undefined
                }
            })
        }
    }
})