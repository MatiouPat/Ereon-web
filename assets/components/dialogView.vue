<template>
    <div class="dialog-box">
        <div class="messages" ref="messages">
            <DialogMessage v-for="(message, index) in messages" :key="index" :dice="message"></DialogMessage>
        </div>
        <div class="textinput">
            <textarea :disabled="isDisabled" :class="{isDisabled: isDisabled}" v-model="computation" @keydown.enter="rollDice"></textarea>
            <div class="dices">
                <div class="dice" @click="addDice('d100')">
                    <img width="24" height="24" src="/build/images/d100.svg" alt="d100">
                    <span>d100</span>
                </div>
                <div class="dice" @click="addDice('d20')">
                    <img width="24" height="24" src="/build/images/d20.svg" alt="d20">
                    <span>d20</span>
                </div>
                <div class="dice" @click="addDice('d12')">
                    <img width="24" height="24" src="/build/images/d12.svg" alt="d12">
                    <span>d12</span>
                </div>
                <div class="dice" @click="addDice('d8')">
                    <img width="24" height="24" src="/build/images/d8.svg" alt="d8">
                    <span>d8</span>
                </div>
                <div class="dice" @click="addDice('d6')">
                    <img width="24" height="24" src="/build/images/d6.svg" alt="d6">
                    <span>d6</span>
                </div>
                <div class="dice" @click="addDice('d4')">
                    <img width="24" height="24" src="/build/images/d4.svg" alt="d4">
                    <span>d4</span>
                </div>
                <div class="dice" @click="addDice('d2')">
                    <img width="24" height="24" src="/build/images/d2.svg" alt="d2">
                    <span>d2</span>
                </div>
            </div>
            <span v-if="isDisabled" class="alert alert-danger">Vous n'avez aucun personnage</span>
            <button type="button" @click="rollDice">Envoyer</button>
        </div>
    </div>
</template>

<script lang="ts">
import axios from 'axios';
import { mapGetters } from 'vuex';
import { defineComponent } from 'vue';
import DialogMessage from './dialogMessage.vue';

    export default defineComponent({
        components: {
            DialogMessage
        },
        data() {
            return {
                /**
                 * The list of all messages
                 */
                messages: [] as [],
                /**
                 * The dice roll requested by the user mapped to the textarea
                 */
                computation: "" as string
            }
        },
        computed: {
            ...mapGetters('user', [
                'getPersonages'
            ]),
            /**
             * If the user has a character in the world
             */
            isDisabled() {
                return !this.getPersonages.length
            }
        },
        methods: {
            /**
             * Call the API to roll a die
             */
            rollDice: function () {
                let computation = this.computation;
                this.computation = '';
                axios.post('/api/dices', {
                    computation: computation,
                    personage: '/api/personages/' + this.getPersonages[0].id
                }).catch(e => {
                    this.messages.push(e.response.data['hydra:description'] + '<br> Exemple - d100+20-4');
                })
            },
            addDice: function (dice) {
                this.computation = dice
            }
        },
        mounted: function() {
            const u = new URL(process.env.MERCURE_PUBLIC_URL);
            u.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/dice');

            const es = new EventSource(u);
            es.onmessage = e => {
                let data = JSON.parse(e.data)
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
        }
    })
</script>

<style scoped>

    .messages {
        height: calc(80dvh - 64px);
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
        height: calc(20dvh - 16px);
    }

    .textinput textarea {
        display: block;
        height: 100%;
        resize: none;
        border: solid 1px #565656;
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
    }

    .dices {
        display: flex;
        justify-content: space-around;
        margin: 0 8px;
    }

    .dice {
        width: 32px;
        text-align: center;
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
    }

    .dice img {
        margin: 0 auto 4px auto;
    }

</style>