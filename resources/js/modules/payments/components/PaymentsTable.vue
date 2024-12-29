<template>
    <DataTable :row-clickable="!!rowClicked" :store="store" :columns="computedColumns" @row-clicked="rowClicked">
        <template v-if="$slots['header']" #header>
            <slot name="header"></slot>
        </template>
        <template v-if="$slots['filters']" #filters>
            <slot name="filters"></slot>
        </template>
    </DataTable>
</template>

<script setup lang="ts">
import DataTable, { DataTableColumn } from "@/components/Table/DataTable.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { useI18n } from "vue-i18n"
import { computed } from "vue"
import PaymentStatus from "@/modules/payments/components/table/PaymentStatus.vue"
import { formatNumber } from "@/logic/helpers"
import { EntryListStore } from "@/store/factories/entryListStore"
import CDate from "@/components/Table/components/CDate.vue"
import AddDebt from "@/modules/payments/components/table/AddDebt.vue"

const { t } = useI18n()
const props = defineProps<{
    store: EntryListStore
    columns?: DataTableColumn[]
    rowClicked?: (row: any) => void
}>()

const computedColumns = computed(() => {
    const columns = [
        { field: "visit.notes", headerName: t("payments.action") },
        {
            field: "amount",
            headerName: t("payments.amount"),
            valueFormatter: (value: any) => {
                return formatNumber(value)
            },
        },
        {
            field: "remaining_amount",
            headerName: t("payments.remainingAmount"),
            textClass: "!text-pink-600",
            valueFormatter: (value: any) => {
                return formatNumber(value)
            },
        },
        { field: "status", headerName: t("payments.status"), cellRenderer: PaymentStatus },
        { field: "date", headerName: t("payments.paymentDate"), cellRenderer: CDate },
        { field: "created_at", headerName: t("patients.createdAt"), cellRenderer: DateTime },
        { field: "", headerName: "", cellRenderer: AddDebt },
    ]
    return props.columns ? props.columns : columns
})
</script>
