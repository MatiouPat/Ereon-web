/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/main.css';

// start the Stimulus application
import './bootstrap';
import Account from './components/account.vue'
import { createApp } from 'vue';
import store from './store';
import { emitter } from './emitter';

let account = createApp({
    components: {
        Account
    }
})


account.provide("$store", store);
account.use(store)
account.provide('emitter', emitter);

account.mount('#content')