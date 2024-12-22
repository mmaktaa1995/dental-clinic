<template>
    <button v-if="!to" v-bind="attrs" type="button" class="relative" :class="{ [classes]: classes }" :disabled="disabled">
        <slot></slot>
    </button>
    <router-link v-else :to="to" class="relative" :class="{ [classes]: classes }">
        <slot></slot>
    </router-link>
</template>

<script setup lang="ts">
import { computed, useAttrs } from "vue"
import { RouteLocationRaw } from "vue-router"
export type ButtonType = "primary" | "info" | "warning" | "error" | "default" | "link"
// Props
const props = withDefaults(
    defineProps<{
        type?: ButtonType
        disabled?: boolean
        to?: RouteLocationRaw
    }>(),
    {
        type: "default",
        disabled: false,
        to: undefined,
    },
)

// Access $attrs and $listeners
const attrs = useAttrs()
const classes = computed(() => {
    const baseStyle = "w-full inline-flex justify-center rounded-md border border-transparent duration-75 transition-all shadow-sm px-4 py-2 text-base font-medium sm:h-10 sm:w-auto sm:text-sm"

    const styles: Record<string, string> = {
        primary: "bg-teal-600 text-white hover:bg-teal-700",
        info: "bg-blue-400 text-white hover:bg-blue-500",
        warning: "bg-yellow-500 text-white hover:bg-yellow-600",
        error: "bg-red-600 text-white hover:bg-red-700",
        link: "text-gray-400 hover:text-teal-600",
        default: "bg-white text-gray-700 hover:bg-gray-50",
    }

    const typeStyle = styles[props.type] || styles.default
    const isDisabled = props.disabled ? `${props.type !== "link" ? "bg-gray-300 text-gray-600" : ""} cursor-not-allowed` : typeStyle

    return `${baseStyle} ${isDisabled}`
})
</script>
