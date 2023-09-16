<template>
    <div class="dialog-box">
        <div class="messages" ref="messages">
            <DialogMessage v-for="(message, index) in messages" :key="index" :dice="message"></DialogMessage>
        </div>
        <div class="textinput">
            <textarea :disabled="isDisabled && !isGameMaster" :style="isDisabled && !isGameMaster ? 'background-color: #C8C8C8;' : ''" v-model="computation" @keydown.enter="rollDice"></textarea>
            <div class="dices">
                <div class="dice" @click="addDice('/r d100')">
                    <img width="24" height="24" src="/build/images/d100.svg" alt="d100">
                    <span>d100</span>
                </div>
                <div class="dice" @click="addDice('/r d20')">
                    <img width="24" height="24" src="/build/images/d20.svg" alt="d20">
                    <span>d20</span>
                </div>
                <div class="dice" @click="addDice('/r d12')">
                    <img width="24" height="24" src="/build/images/d12.svg" alt="d12">
                    <span>d12</span>
                </div>
                <div class="dice" @click="addDice('/r d8')">
                    <img width="24" height="24" src="/build/images/d8.svg" alt="d8">
                    <span>d8</span>
                </div>
                <div class="dice" @click="addDice('/r d6')">
                    <img width="24" height="24" src="/build/images/d6.svg" alt="d6">
                    <span>d6</span>
                </div>
                <div class="dice" @click="addDice('/r d4')">
                    <img width="24" height="24" src="/build/images/d4.svg" alt="d4">
                    <span>d4</span>
                </div>
                <div class="dice" @click="addDice('/r d2')">
                    <img width="24" height="24" src="/build/images/d2.svg" alt="d2">
                    <span>d2</span>
                </div>
            </div>
            <span v-if="isDisabled && !isGameMaster" class="alert alert-danger">Vous n'avez aucun personnage</span>
            <select v-if="isGameMaster">
                <option>MJ</option>
                <option :key="key" v-for="(personage, key) in nonPlayerPersonages">{{ personage.name }}</option>
            </select>
            <select v-if="!isGameMaster && getPersonages">
                <option :key="key" v-for="(personage, key) in getPersonages">{{ personage.name }}</option>
            </select>
            <button type="button" @click="rollDice">Envoyer</button>
        </div>
    </div>
</template>

<script lang="ts">
import { mapGetters } from 'vuex';
import { defineComponent } from 'vue';
import DialogMessage from './dialogMessage.vue';
import { Dice } from '../entity/dice';
import { DiceRepository } from '../repository/diceRepository';
import { Personage } from '../entity/personage';
import { PersonageRepository } from '../repository/personageRepository';

    export default defineComponent({
        components: {
            DialogMessage
        },
        data() {
            return {
                diceRepository: new DiceRepository as DiceRepository,
                personageRepository: new PersonageRepository as PersonageRepository,
                /**
                 * The list of all messages
                 */
                messages: [] as any[],
                /**
                 * The dice roll requested by the user mapped to the textarea
                 */
                computation: "" as string,
                nonPlayerPersonages: [] as Personage[]
            }
        },
        computed: {
            ...mapGetters('user', [
                'getPersonages',
                'isGameMaster',
                "getWorld"
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
                let computation = this.computation.split(' ');
                if(computation[1]) {
                    this.diceRepository.createDice(this.getPersonages[0]['@id'], computation[1]).catch(e => {
                        this.messages.push(e.response.data['hydra:description'] + '<br> Exemple - /r d100+20-4');
                        this.scollToBottom()
                    })
                }else {
                    this.messages.push('Demande non valide <br> Exemple - /r d100+20-4');
                    this.scollToBottom()
                }
            },
            addDice: function (dice: string) {
                this.computation = dice
            },
            scollToBottom: function() {
                let messages = this.$refs.messages as HTMLElement;
                setTimeout(() => {
                    messages.scrollTo({top: messages.scrollHeight, behavior: 'smooth'});
                    this.computation = '';
                },2)
            }
        },
        mounted: function() {
            const u = new URL(process.env.MERCURE_PUBLIC_URL!);
            u.searchParams.append('topic', 'https://lescanardsmousquetaires.fr/dice');

            const es = new EventSource(u);
            es.onmessage = e => {
                let data = JSON.parse(e.data)
                this.messages.push(data);
                this.scollToBottom();
            }

            this.diceRepository.findAllDices().then(res => {
                res.forEach((dice: Dice) => {
                    this.messages.push(dice)
                });
            })

            this.personageRepository.findNonPlayerPersonagesByWorld(this.getWorld.id).then(res => {
                this.nonPlayerPersonages = res
            })
        }
    })
</script>

<style scoped>

    .messages {
        height: calc(80dvh - 64px);
        overflow-y: scroll;
        margin-bottom: 16px;
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
        margin: 0 8px;
    }

    .dice {
        display: flex;
        flex-direction: column;
        width: 40px;
        text-align: center;
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
    }


    .dice img {
        margin: 0 auto 4px auto;
    }

    .dark .textinput textarea {
        background-color: #2b2a33;
    }

</style>