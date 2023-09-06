import { createStore } from 'vuex'
import map from './modules/map'
import user from './modules/user'
import music from './modules/music'

export default createStore({
    modules: {
        map,
        user,
        music
    },
    strict: false
})