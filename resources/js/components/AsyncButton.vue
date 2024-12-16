<template>
    <CButton :type :disabled v-bind="attrs">
        <span v-if="loading" class="absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%)">
            <c-loader color="white" width="32"></c-loader>
        </span>

        <span :class="{ invisible: loading }">
            <slot></slot>
        </span>
    </CButton>
</template>

<script setup lang="ts">
import { useAttrs } from "vue"

// Props
type ButtonType = "primary" | "warning" | "error" | "default"

withDefaults(
    defineProps<{
        type?: ButtonType
        loading: boolean
        disabled?: boolean
    }>(),
    {
        disabled: false,
        type: "default",
    },
)

// Access $attrs and $listeners
const attrs = useAttrs()
</script>
