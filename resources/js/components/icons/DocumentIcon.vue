<template>
    <svg viewBox="0 0 24 24" :class="sizeClass">
        <path class="fill-current text-gray-300" d="M6 2h6v6c0 1.1.9 2 2 2h6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2z" />
        <polygon class="fill-current text-gray-500" points="14 2 20 8 14 8" />
    </svg>
</template>

<script setup lang="ts">
import { useSizeClass } from "@/composables/sizable.js"

const { size } = defineProps<{
    size: number | string
}>()
const sizeClass = useSizeClass(size)
</script>
