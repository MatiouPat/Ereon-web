<template>
    <div class="dialog-box">
        <div class="messages" ref="messages">
            <DialogMessage v-for="message in messages" :key="message.id" :dice="message"></DialogMessage>
        </div>
        <div class="textinput">
            <textarea :disabled="isDisabled" :class="{isDisabled: isDisabled}" v-model="computation" @keydown.enter="rollDice"></textarea>
            <span v-if="isDisabled" class="alert alert-danger">Vous n'avez aucun personnage</span>
            <button type="button" @click="rollDice">Envoyer</button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import DialogMessage from './dialogMessage.vue'
    import { mapGetters } from 'vuex';

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
        computed: {
            ...mapGetters('user', [
                'getPersonages'
            ]),
            isDisabled() {
                return !this.getPersonages.length
            }
        },
        methods: {
            rollDice: function () {
                let computation = this.computation;
                this.computation = '';
                axios.post('/api/dices', {
                    computation: computation,
                    personage: '/api/personages/' + this.getPersonages[0].id
                }).catch(e => {
                    this.messages.push(e.response.data['hydra:description'] + '<br> Exemple - d100+20-4');
                })
            }
        },
        mounted: function() {
            const u = new URL(process.env.MERCURE_PUBLIC_URL);
            u.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/dice');

            const es = new EventSource(u);
            es.onmessage = e => {
                let data = JSON.parse(e.data)
                console.log(data)
                this.messages.push(data);
            }

            axios.get('/api/dices')
                .then(response => {
                    let dices = response.data['hydra:member']
                    dices.forEach(dice => {
                        this.messages.push(dice)
                    });
                }) 
        },
        updated() {
            this.$refs.messages.scrollTop = this.$refs.messages.scrollTopMax
        },
    }
</script>

<style scoped>

    .messages {
        height: calc(85dvh - 64px);
        overflow-y: scroll;
    }

    .messages::-webkit-scrollbar {
        width: .4em;
    }
    
    .messages::-webkit-scrollbar-thumb {
        background-color: #666666;
    }


    .textinput {
        display: flex;
        flex-direction: column;
        justify-content: end;
        gap: 8px;
        height: calc(15dvh - 16px);
    }

    .textinput textarea {
        display: block;
        height: 100%;
        resize: none;
        border: solid 1px #565656;
        border-radius: 8px;
    }

    .textinput textarea.isDisabled {
        background-color: #C8C8C8;
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