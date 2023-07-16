<template>
    <div class="message" v-if="dice.person">
        <div class="message-step">
            <span class="message-author">{{ dice.person.name }}: </span>
            <span>{{ dice.computation }}</span>
        </div>
        <span class="message-step">{{ calculation }}</span><br>
        <span class="message-step message-result">={{ dice.finalValue }}</span>
    </div>
    <div class="message error" v-else>
        <span v-html="dice"></span>
    </div>
</template>

<script>
    const axios = require('axios');

    export default {
        props: [
            'dice'
        ],
        computed: {
            calculation() {
                return '(' + this.dice.brutValue + ')' + this.dice.computation.replace(/d[1-9]\d*/, '')
            }
        }
    }
</script>

<style>
    .message {
        display: block;
        margin: 4px 0;
        padding: 8px;
        background-color: #EFEFEF;
    }

    .message-step {
        margin: 4px 0;
    }

    .message-author {
        font-weight: 700;
    }

    .message-result {
        display: inline-block;
        border: 1px solid #D68836;
        padding: 4px;
        font-weight: 700;
    }

    .error {
        color: #721c24;
        background-color: #f8d7da;
    }
</style>