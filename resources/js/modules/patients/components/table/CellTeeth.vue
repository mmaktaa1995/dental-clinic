<template>
    <div>{{ teeth }}</div>
</template>

<script setup lang="ts">
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { useSettingsStore } from "@/modules/global/settingsStore"
import { computed } from "vue"

const props = defineProps<{
    value: any
    entry: Record<string, any>
    column: DataTableColumn
}>()
const settingsStore = useSettingsStore()

const teeth = computed(() => {
    // this means the teethIds is empty, otherwise we're getting it as an object
    if (Array.isArray(props.value)) {
        return "-"
    }

    const teethIds = Object.values(props.value)
    return settingsStore.teeth
        .filter((tooth) => teethIds.includes(tooth.id))
        .map((tooth) => `T${tooth.number}`)
        .join(", ")
})
</script>
