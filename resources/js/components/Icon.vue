<template>
    <span :class="[size ? sizeClass : '', iconClass]"> </span>
</template>

<script setup lang="ts">
import { useSizeClass } from "@/composables/sizable.js"
import { computed, useSlots } from "vue"

const slots = useSlots()

const { size } = defineProps<{
    size?: number | string
}>()
const sizeClass = useSizeClass(size)

const iconClass = computed(() => {
    return slots.default?.()[0].children
})
</script>
