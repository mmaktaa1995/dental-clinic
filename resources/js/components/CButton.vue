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
export type ButtonType = "primary" | "accent" | "info" | "warning" | "error" | "default" | "link"
// Props
const props = withDefaults(
    defineProps<{
        type?: ButtonType
        disabled?: boolean
        to?: RouteLocationRaw
        sm?: boolean
    }>(),
    {
        type: "default",
        disabled: false,
        sm: false,
        to: undefined,
    },
)

// Access $attrs and $listeners
const attrs = useAttrs()
const classes = computed(() => {
    let baseStyle = "min-w-[60px] w-full rounded-md border duration-150 transition-colors shadow-sm px-4 py-2 font-medium sm:h-10 sm:w-auto sm:text-sm"

    if (props.sm) {
        baseStyle = baseStyle.replace("sm:h-10", "").replace("px-4", "px-2").replace("py-2", "py-1").replace("text-base", "").replace("sm:text-sm", "text-xs")
    }
    const styles: Record<string, string> = {
        primary: "bg-cyan-700 text-white hover:bg-cyan-800",
        accent: "bg-pink-600 text-white hover:bg-pink-700",
        info: "bg-blue-400 text-white hover:bg-blue-500",
        warning: "bg-yellow-500 text-white hover:bg-yellow-600",
        error: "bg-red-600 text-white hover:bg-red-700",
        link: "text-gray-300 hover:text-cyan-700",
        default: "bg-white text-gray-700 hover:bg-gray-50",
    }

    if (props.type !== "default") {
        baseStyle += " border-transparent"
    }

    const typeStyle = styles[props.type] || styles.default
    const isDisabled = props.disabled ? `${props.type !== "link" ? "bg-gray-300 text-gray-600" : ""} cursor-not-allowed` : typeStyle

    return `${baseStyle} ${isDisabled}`
})
</script>
