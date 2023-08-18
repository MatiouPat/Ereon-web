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


editor.provide("$store", store);
editor.use(store)
editor.provide('emitter', emitter);

editor.mount('#editor')

let dialogBox = createApp({
    components: {
        RightSideBox
    }
})

dialogBox.provide("$store", store);
dialogBox.use(store)
dialogBox.provide('emitter', emitter);

dialogBox.mount('#right-side-box')