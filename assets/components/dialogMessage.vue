<template>
    <div class="message" v-if="dice.launcher">
        <div class="message-step">
            <span v-if="dice.personage" class="message-author">{{ dice.personage.name }}: </span>
            <span v-else class="message-author">MJ: </span>
            <span>{{ dice.computation }}</span>
        </div>
        <span class="message-step">{{ calculation }}</span><br>
        <span class="message-step message-result">={{ dice.finalValue }}</span>
    </div>
    <div class="message error" v-else>
        <span v-html="dice"></span>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

    export default defineComponent({
        props: [
            'dice'
        ],
        computed: {
            /**
             * Render view for dice calculation
             */
            calculation() {
                return '(' + this.dice.brutValue + ')' + this.dice.computation.replace(/d[1-9]\d*/, '')
            }
        }
    })
</script>

<style scoped>
    .message {
        display: block;
        margin: 4px 0;
        padding: 8px;
    }

    .message-step {
        margin: 4px 0;
    }

    .message-author {
        font-weight: 700;
    }

    .message-result {
        display: inline-block;
        border: 1px solid #D87D40;
        padding: 4px;
        font-weight: 700;
    }

    .error {
        color: #721c24;
        background-color: #f8d7da;
    }
</style>