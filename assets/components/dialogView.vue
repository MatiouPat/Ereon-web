<template>
    <div class="dialog-box">
        <div class="messages" ref="messages">
            <DialogMessage v-for="(message, index) in messages" :key="index" :dice="message"></DialogMessage>
        </div>
        <div class="textinput">
            <textarea :disabled="isDisabled && !isGameMaster" :style="isDisabled && !isGameMaster ? 'background-color: #C8C8C8;' : ''" v-model="computation" @keypress.enter.prevent="rollDice"></textarea>
            <div class="dices">
                <div class="dice" @click="addDice('/r d100')">
                    <img width="24" height="24" :src="getIsDarkTheme ? '/build/images/d100_white.svg' : '/build/images/d100_black.svg'" alt="d100">
                    <span>d100</span>
                </div>
                <div class="dice" @click="addDice('/r d20')">
                    <img width="24" height="24" :src="getIsDarkTheme ? '/build/images/d20_white.svg' : '/build/images/d20_black.svg'" alt="d20">
                    <span>d20</span>
                </div>
                <div class="dice" @click="addDice('/r d12')">
                    <img width="24" height="24" :src="getIsDarkTheme ? '/build/images/d12_white.svg' : '/build/images/d12_black.svg'" alt="d12">
                    <span>d12</span>
                </div>
                <div class="dice" @click="addDice('/r d8')">
                    <img width="24" height="24" :src="getIsDarkTheme ? '/build/images/d8_white.svg' : '/build/images/d8_black.svg'" alt="d8">
                    <span>d8</span>
                </div>
                <div class="dice" @click="addDice('/r d6')">
                    <img width="24" height="24" :src="getIsDarkTheme ? '/build/images/d6_white.svg' : '/build/images/d6_black.svg'" alt="d6">
                    <span>d6</span>
                </div>
                <div class="dice" @click="addDice('/r d4')">
                    <img width="24" height="24" :src="getIsDarkTheme ? '/build/images/d4_white.svg' : '/build/images/d4_black.svg'" alt="d4">
                    <span>d4</span>
                </div>
                <div class="dice" @click="addDice('/r d2')">
                    <img width="24" height="24" :src="getIsDarkTheme ? '/build/images/d2_white.svg' : '/build/images/d2_black.svg'" alt="d2">
                    <span>d2</span>
                </div>
            </div>
            <select-input v-if="launchersChoice.length" :choices="launchersChoice" :model-value="launcher" :background="getIsDarkTheme ? '#364049' : '#FFFFFF'" :has-default="false" @update:model-value="(modelValue) => { launcher = modelValue }" ></select-input>
            <span v-else class="alert alert-danger">Vous n'avez aucun personnage</span>
            <button class="btn btn-primary" type="button" @click="rollDice">Envoyer</button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import DialogMessage from './dialogMessage.vue';
import { Dice } from '../entity/dice';
import { DiceRepository } from '../repository/diceRepository';
import { Personage } from '../entity/personage';
import { PersonageRepository } from '../repository/personageRepository';
import SelectInput from './form/selectInput.vue';
import { mapState } from 'pinia';
import { useUserStore } from '../store/user';

    export default defineComponent({
        components: {
            DialogMessage,
            SelectInput
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
                nonPlayerPersonages: [] as Personage[],
                launchersChoice: [] as  {
                    id?: string
                    value?: string
                }[],
                launcher: 0 as number
            }
        },
        computed: {
            ...mapState(useUserStore, [
                'getPersonages',
                'isGameMaster',
                'getWorld',
                'getUserId',
                'getIsDarkTheme'
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
                    if(this.launcher === 0) {
                        this.diceRepository.createDice(this.getUserId, this.getWorld.id, computation[1], undefined).catch(e => {
                            this.messages.push(e.response.data['hydra:description'] + '<br> Exemple - /r d100+20-4');
                            this.scollToBottom()
                        })
                    }else {
                        this.diceRepository.createDice(this.getUserId, this.getWorld.id, computation[1], this.launcher).catch(e => {
                            this.messages.push(e.response.data['hydra:description'] + '<br> Exemple - /r d100+20-4');
                            this.scollToBottom()
                        })
                    }
                    
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

            if(this.isGameMaster){
                this.launchersChoice.push({
                    id: '0',
                    value: 'MJ'
                });
                this.personageRepository.findNonPlayerPersonagesByWorld(this.getWorld.id).then(personages => {
                    personages.forEach(personage => {
                        this.launchersChoice.push({
                            id: personage.id?.toString(),
                            value: personage.name
                        })
                    })
                })
            }else {
                this.getPersonages.forEach((personage: Personage) => {
                    this.launchersChoice.push({
                        id: personage.id?.toString(),
                        value: personage.name
                    });
                    this.launcher = personage.id!;
                });
            }
        }
    })
</script>

<style scoped>

    .dialog-box {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .messages {
        flex-grow: 1;
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
        height: calc(25dvh - 16px);
    }

    .textinput textarea {
        display: block;
        height: 100%;
        resize: none;
        border: solid 1px #565656;
        background-color: #FFFFFF;
    }

    .dices {
        display: flex;
        margin: 0 8px 24px 8px;
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
        background-color: #364049;
        border: solid 1px #F3F4F4;
    }

    .btn {
        width: 100%;
    }

</style>