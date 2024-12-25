<template>
    <c-container>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 max-w-7xl mx-auto">
            <div class="">
                <label class="block text-sm font-medium text-gray-700 text-right">{{ $t("payments.totalRemainingAmount") }}</label>
                <label class="block text-2xl font-medium text-pink-600 text-right">{{ formattedValue(debitsStore.totalRemainingPayments) }}</label>
            </div>
        </div>
        <hr class="my-4" />
        <div>
            <PaymentsTable :store="debitsStore" :columns="columns" />
        </div>
    </c-container>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import PaymentsTable from "@/modules/payments/components/PaymentsTable.vue"
import { formatNumber } from "@/logic/helpers"
import PaymentStatus from "@/modules/payments/components/table/PaymentStatus.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { useI18n } from "vue-i18n"
import { useDebitsStore } from "@/modules/debits/store"

const debitsStore = useDebitsStore()
const { t } = useI18n()
const formattedValue = (value: number) => {
    return formatNumber(value)
}

const columns = [
    { field: "patient.name", headerName: t("patients.name") },
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
    { field: "created_at", headerName: t("payments.date"), cellRenderer: DateTime },
]

useEntryListUpdater(`/debits`, debitsStore, async (response) => {
    debitsStore.totalRemainingPayments = response.totalDebits
})
</script>
