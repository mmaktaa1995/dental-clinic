<template>
    <div>
        <div>
            <PaymentsTable :store="debitsStore" :columns="columns">
                <template #header>
                    <div>
                        <div class="font-semibold text-lg">{{ $t("payments.debits") }}</div>
                        <div class="mt-2">
                            <label class="block text-sm font-medium text-gray-700 text-right">{{ $t("payments.totalRemainingAmount") }}</label>
                            <label class="block text-2xl font-medium text-rose-600 text-right">{{ formattedValue(debitsStore.totalRemainingPayments) }}</label>
                        </div>
                    </div>
                </template>
            </PaymentsTable>
        </div>
    </div>
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
        textClass: "!text-rose-600",
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
