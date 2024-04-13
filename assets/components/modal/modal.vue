<template>
    <Teleport to="body">
        <div class="modal-wrapper" :class="{ popup: type == 'popup'}" v-show="isDisplayed">
            <div class="modal">
                <div class="modal-header" v-if="type == 'modal'">
                        <h2>{{ modalTitleMessage }}</h2>
                        <img width="24" height="24" @click.stop="$emit('modal:close')" src="build/images/icons/close.svg" alt="Fermer">
                </div> 
                <div class="modal-body">
                    <slot name="modal-body"></slot>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" @click.stop="$emit('modal:close')">Annuler</button>
                    <button type="button" class="btn btn-primary" @click="$emit('modal:validation')">{{ modalValidationMessage }}</button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script lang="ts">
import { defineComponent } from "vue";

const ModalType = Object.freeze({
    MODAL: "modal" as string,
    POPUP: "popup" as string
})

export default defineComponent({
    props: {
        isDisplayed: {
            type: Boolean,
            default: false
        },
        type: {
            validator(value: string) {
                return Object.values(ModalType).includes(value)
            },
            default: ModalType.MODAL
        },
        modalTitleMessage: {
            type: String
        },
        modalValidationMessage: {
            type: String
        }
    },
    emits: ['modal:close', 'modal:validation']
})
</script>

<style scoped>
    .modal-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 5;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100dvh;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal {
        display: grid;
        grid-template-rows: min-content auto min-content;
        min-width: 256px;
        max-width: 800px;
        min-height: 128px;
        max-height: 480px;
        background-color: #FFFFFF;
        font-size: 1rem !important;
    }

    .popup .modal {
        grid-template-rows: auto min-content;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border-bottom: solid 1px #73808C;
        background-color: #BBBFC3;
        height: 40px;
    }

    .modal-body {
        display: flex;
        flex-direction: column;
        align-content: stretch;
        align-items: stretch;
        gap: 16px;
        height: 100%;
        width: 100%;
        padding: 24px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 48px;
        gap: 8px;
        padding: 8px;
        border-top: solid 1px #73808C;
    }

    .dark .modal-header {
        background-color: #0E1318;
    }

    .dark .modal-body, .dark .modal-footer {
        background-color: #4F5A64;
    }
</style>