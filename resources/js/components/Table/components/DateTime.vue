<template>
    <div v-if="dateParts" class="flex flex-col justify-center h-full text-gray-500">
        <div class="font-medium text-sm">{{ dateParts.day }}</div>
        <div v-if="dateParts.time" class="text-sm">{{ dateParts.time }}</div>
    </div>
</template>
<script setup lang="ts">
import { computed } from "vue"
import { format, parseISO } from "date-fns"
import { DataTableColumn } from "@/components/Table/DataTable.vue"

const props = defineProps<{
    value: any
    entry: Record<string, any>
    column: DataTableColumn
}>()

const dateParts = computed(() => {
    if (!props.value) {
        return null
    }
    const date = parseISO(props.value)
    return {
        day: format(date, "dd.MM.yyyy"),
        time: format(date, "HH:mm"),
    }
})
</script>
