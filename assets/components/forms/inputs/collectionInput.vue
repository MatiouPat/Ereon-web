<template>
    <div>
        <label v-if="label">{{ label }}</label>
        <TransitionGroup class="form-collection" name="form-collection" tag="ul" :css="isReady">
            <li class="form-collection-item" v-for="(item, index) in modelValue" :key="item.id">
                <slot :item="item"></slot>
                <i class="form-collection-delete-icon" v-if="!hasEmptyElement(item)" @click="deleteItem(index)"><img :src="getIsDarkTheme ? '/build/images/icons/close_white.svg' : '/build/images/icons/close_black.svg'" width="20" height="20" alt="Supprimer"></i>
                <i class="form-collection-delete-icon" v-else></i>
            </li>
        </TransitionGroup>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { mapState } from "pinia";
import { useUserStore } from "../../../store/user";

export default defineComponent({
    name: "CollectionInput",
    inject: {
        step: {
            from: 'step',
            default: null
        }
    },
    provide() {
        return {
            collection: this
        }
    },
    data() {
        return {
            inputs: [] as [],
            isReady: false,
            inputsPerCollection: 0 as number
        }
    },
    props: {
        label: {
            type: String,
            default: ""
        },
        modelValue: {
            type: Array<{ id:number }>,
            required: true
        }
    },
    computed: {
        ...mapState(useUserStore, [
            'getIsDarkTheme'
        ])
    },
    watch: {
        modelValue: {
            handler() {
                let hasEmptyElement: boolean = false;
                for(let i = this.modelValue.length - 1; i >= 0; i--) {
                    if(this.hasEmptyElement(this.modelValue[i])) {
                        if(hasEmptyElement) {
                            this.deleteItem(i)
                        }
                        hasEmptyElement = true;
                    }
                }
                if(!hasEmptyElement) {
                    this.$emit('add:modelValue')
                }
            },
            deep: true
        }
    },
    methods: {
        registerInput(input): void
        {
            this.inputs.push(input);
        },
        validate(): boolean
        {
            let isValid = true;
            for(let i = 0; i < this.inputs.length-this.inputs.length/this.modelValue.length; i++) {
                const result = this.inputs[i].validate();
                isValid = result && isValid;
            }
            return isValid;
        },
        hasEmptyElement: function(element: Object) {
            let hasEmptyElement = true;
            Object.entries(element).forEach(entry => {
                //Pass id field
                if(entry[0] === "id") {
                    return;
                }
                let value = entry[1];
                //Condition on simple field
                if(value === "" || value === null || value === undefined || value === 0) {
                    return;
                }
                if(typeof value === "object" && Object.entries(value).length === 0) {
                    return;
                }
                hasEmptyElement = false;
            })
            return hasEmptyElement;
        },
        deleteItem: function(itemId: number) {
            this.inputs[this.inputsPerCollection * itemId + this.inputsPerCollection].focusInput();
            this.inputs.splice((this.inputsPerCollection)*itemId, this.inputsPerCollection);
            this.$emit('remove:modelValue', itemId)
        }
    },
    emits: ['add:modelValue', 'remove:modelValue'],
    mounted() {
        if(this.step) {
            this.step.registerInput(this);
        }
        this.$nextTick(() => {
            this.isReady = true;
            if(this.modelValue.length === 0) {
                this.$emit('add:modelValue')
            }
            this.inputsPerCollection = this.inputs.length/this.modelValue.length;
        });
    }
})

</script>

<style scoped>

.form-collection {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-collection-item {
    display: flex;
    gap: 4px;
    width: 100%;
    box-sizing: border-box;
}

.form-collection-move,
.form-collection-enter-active,
.form-collection-leave-active {
    transition: all 0.4s cubic-bezier(0.55, 0, 0.1, 1), opacity 0.1s cubic-bezier(0.55, 0, 0.1, 1);
}
.form-collection-enter-from,
.form-collection-leave-to {
    opacity: 0;
}

.form-collection-leave-active {
    position: absolute;
}

.form-collection-delete-icon {
    align-self: flex-end;
    width: 40px;
    height: 20px;
    margin-bottom: 2px;
}

.form-collection-item:has(.form-group.error) .form-collection-delete-icon {
    align-self: center;
}

</style>