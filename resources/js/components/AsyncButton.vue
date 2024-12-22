<template>
    <CButton :to :type :disabled :class="{ 'cursor-not-allowed': loading }">
        <span v-if="loading" class="absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%)">
            <c-loader color="white" width="32"></c-loader>
        </span>

        <span v-if="!loading">
            <slot></slot>
        </span>
    </CButton>
</template>

<script setup lang="ts">
import { ButtonType } from "@/components/CButton.vue"
import { RouteLocationNormalized } from "vue-router"

withDefaults(
    defineProps<{
        type?: ButtonType
        loading?: boolean
        disabled?: boolean
        to?: RouteLocationNormalized
    }>(),
    {
        disabled: false,
        loading: false,
        type: "default",
        to: undefined,
    },
)
</script>
