import './styles/home.css';
import Editor from './components/editor.vue'
import RightSideBox from './components/rightSideBox.vue'
import MapBox from './components/mapBox.vue'
import { createApp } from 'vue';
import { emitter } from './emitter';
import { pinia } from './store/pinia';

let editor = createApp({
    components: {
        Editor,
        MapBox
    }
})

editor.use(pinia);
editor.provide('emitter', emitter);

editor.mount('#editor');

let dialogBox = createApp({
    components: {
        RightSideBox
    }
})

dialogBox.use(pinia);
dialogBox.provide('emitter', emitter);

dialogBox.mount('#right-side-box');