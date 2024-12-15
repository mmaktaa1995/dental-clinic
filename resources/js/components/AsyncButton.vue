<template>
    <button v-bind="attrs" :type="type" class="relative" :class="{ 'cursor-not-allowed': loading }" :disabled="disabled">
        <span v-if="loading" class="absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%)">
            <c-loader color="white" width="32"></c-loader>
        </span>

        <span :class="{ invisible: loading }">
            <slot></slot>
        </span>
    </button>
</template>

<script setup>
import { useAttrs } from "vue"

// Props
defineProps({
    type: {
        type: String,
        default: "button",
    },
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
})

// Access $attrs and $listeners
const attrs = useAttrs()
</script>
