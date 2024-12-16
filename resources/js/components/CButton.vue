<template>
    <button v-bind="attrs" type="button" class="relative" :class="{ 'cursor-not-allowed': loading, [classes]: classes }" :disabled="disabled">
        <slot></slot>
    </button>
</template>

<script setup lang="ts">
import { computed, useAttrs } from "vue"

// Props
const props = withDefaults(
    defineProps<{
        type?: "primary" | "warning" | "error" | "default"
        disabled?: boolean
    }>(),
    {
        type: "default",
        disabled: false,
    },
)

// Access $attrs and $listeners
const attrs = useAttrs()
const classes = computed(() => {
    switch (props.type) {
        case "primary":
            return "w-full inline-flex justify-center rounded-md border border-transparent duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
        case "warning":
            return "w-full inline-flex justify-center rounded-md border border-transparent duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
        case "error":
            return "w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mr-3 sm:w-auto sm:text-sm"
        default:
            return "w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm"
    }
})
</script>
