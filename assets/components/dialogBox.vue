<template>
    <div class="dialog-box">
        <div class="messages" ref="messages">
            <DialogMessage v-for="message in messages" :key="message.id" :dice="message"></DialogMessage>
        </div>
        <div class="textinput">
            <textarea v-model="computation" @keydown.enter="rollDice"></textarea>
            <button type="button" @click="rollDice">Envoyer</button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import DialogMessage from './dialogMessage.vue'

    export default {
        components: {
            DialogMessage
        },
        data() {
            return {
                messages: [],
                computation: ''
            }
        },
        props: [
            'dices'
        ],
        methods: {
            rollDice: function () {
                let computation = this.computation;
                this.computation = '';
                axios.post('/api/dices', {
                    computation: computation,
                    person: '/api/people/1'
                }).catch(e => {
                    this.messages.push(e.response.data['hydra:description'] + '<br> Exemple - d100+20-4');
                })
            }
        },
        mounted: function() {
            const u = new URL('https://lescanardsmousquetaires.fr:3000/.well-known/mercure');
            u.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/dice');

            const es = new EventSource(u);
            es.onmessage = e => {
                let data = JSON.parse(e.data)
                this.messages.push(data);
            }

            this.dices.forEach(dice => {
                this.messages.push(dice)
            });
        },
        updated() {
            this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight
        },
    }
</script>

<style scoped>

    .dialog-box {
        padding: 16px 8px;
    }

    .messages {
        height: calc(85dvh - 16px);
        overflow-y: scroll;
    }

    .textinput {
        display: flex;
        flex-direction: column;
        justify-content: end;
        height: calc(15dvh - 16px);
    }

    .textinput textarea {
        display: block;
        height: 100%;
        resize: none;
    }

    .textinput button {
        display: inline-block;
        border: none;
        border-radius: 24px;
        background-color: #D68836;
        color: #fff;
        padding: 8px;
        font-weight: 700;
        font-size: 1rem;
        margin-top: 16px;
    }

</style>