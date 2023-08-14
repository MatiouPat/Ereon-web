import './styles/home.css';
import Editor from './components/editor.vue'
import RightSideBox from './components/rightSideBox.vue'
import MapBox from './components/mapBox.vue'
import { createApp } from 'vue';
import store from './store'
import { emitter } from './emitter';

let editor = createApp({
    components: {
        Editor,
        MapBox
    }
})

editor.use(store)

editor.mount('#editor')

let dialogBox = createApp({
    components: {
        RightSideBox
    }
})

dialogBox.use(store)
dialogBox.config.globalProperties.emitter = emitter;

dialogBox.mount('#right-side-box')