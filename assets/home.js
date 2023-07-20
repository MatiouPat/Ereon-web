import './styles/home.css';
import Vue from 'vue';
import Editor from './components/editor.vue'
import DialogBox from './components/dialogBox.vue'
import MapBox from './components/mapBox.vue'

new Vue({
    el: '#editor',
    components: {
        Editor,
        MapBox
    }
})

new Vue({
    el: '#dialog-box',
    components: {
        DialogBox
    }
})