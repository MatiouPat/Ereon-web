<template>
    <div :title="title">
        <slot></slot>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        title: {
            type: String,
            default: ''
        }
    }
})

</script>

<style scoped>

.undisplayed {
    display: none;
}

</style>