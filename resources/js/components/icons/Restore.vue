<template>
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="sizeClass">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3" />
    </svg>
</template>

<script setup lang="ts">
import { useSizeClass } from "@/composables/sizable.js"

const { size } = defineProps<{
    size: number | string
}>()
const sizeClass = useSizeClass(size)
</script>
