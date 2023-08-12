import { createStore } from 'vuex'
import map from './modules/map'
import user from './modules/user'

export default createStore({
    modules: {
        map,
        user
    },
    strict: false
})