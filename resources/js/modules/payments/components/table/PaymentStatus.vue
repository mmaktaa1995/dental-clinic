<template>
    <div v-if="status !== null" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium" :class="[statusClass]">
        {{ status }}
    </div>
</template>
<script setup lang="ts">
import { computed } from "vue"
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { useI18n } from "vue-i18n"

const props = defineProps<{
    value: any
    entry: Record<string, any>
    column: DataTableColumn
}>()
const { t } = useI18n()

const status = computed(() => {
    if (props.value === null) {
        return null
    }

    const statuses = {
        1: t("payments.statuses.success"),
        2: t("payments.statuses.failed"),
        3: t("payments.statuses.refunded"),
    }

    return statuses[props.value] ?? null
})
const statusClass = computed(() => {
    if (props.value === null) {
        return null
    }

    const statuses = {
        1: "bg-teal-50 text-teal-800 ring-1 ring-inset ring-teal-600/20",
        2: "bg-red-50 text-red-800 ring-1 ring-inset ring-red-600/20",
        3: "bg-blue-50 text-blue-800 ring-1 ring-inset ring-blue-600/20",
    }

    return statuses[props.value] ?? null
})
</script>
