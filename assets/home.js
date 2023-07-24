import './styles/home.css';
import Editor from './components/editor.vue'
import DialogBox from './components/dialogBox.vue'
import MapBox from './components/mapBox.vue'
import { createApp } from 'vue';
import store from './store'

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
        DialogBox
    }
})

dialogBox.mount('#dialog-box')