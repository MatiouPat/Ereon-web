import './styles/home.css';
import Vue from 'vue';
import Editor from './components/editor.vue'
import DialogBox from './components/dialogBox.vue'

new Vue({
    el: '#editor',
    components: {
        Editor
    }
})

new Vue({
    el: '#dialog-box',
    components: {
        DialogBox
    }
})