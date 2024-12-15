<template>
    <div>
        <div class="flex items-center mt-4" style="height: 150px">
            <c-icon-loader :class="`mx-auto block text-${color}-500`" :style="{ width: `${width}px` }" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" :fill="fillColor" />
        </div>

        <div class="mt-4 text-center max-w-xl mx-auto">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue"

// Define component props using Composition API
const props = defineProps({
    color: {
        type: String,
        default: "gray",
    },
    width: {
        type: [Number, String],
        default: 50,
    },
    fillColor: {
        type: String,
        default: "currentColor",
    },
})
</script>
