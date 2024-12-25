<template>
    <c-container>
        <PaymentsTable :columns="columns" :store="paymentsStore" :row-clicked="rowClicked">
            <template #header>
                <div>
                    <div class="font-semibold text-lg">{{ $t("payments.title") }}</div>
                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700 text-right">{{ $t("payments.totalAmount") }}</label>
                        <label class="block text-2xl font-medium text-teal-600 text-right">{{ formattedValue(paymentsStore.totalPayments) }}</label>
                    </div>
                </div></template
            >
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="paymentsStore.query" class="w-100" :label="$t('patients.name')" name="name"></CTextField>
                    <CDatePicker v-model="paymentsStore.from_date" :label="$t('global.fromDate')" name="from_date"></CDatePicker>
                    <CDatePicker v-model="paymentsStore.to_date" :label="$t('global.toDate')" name="to_date"></CDatePicker>
                </div>
            </template>
        </PaymentsTable>
    </c-container>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { useI18n } from "vue-i18n"
import PaymentsTable from "@/modules/payments/components/PaymentsTable.vue"
import { formatNumber } from "@/logic/helpers.js"
import { useRouter } from "vue-router"
import PaymentStatus from "@/modules/payments/components/table/PaymentStatus.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { usePaymentsStore } from "@/modules/payments/store"

const paymentsStore = usePaymentsStore()
const router = useRouter()
const { t } = useI18n()

const columns: DataTableColumn[] = [
    { field: "patient.name", headerName: t("patients.name") },
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

const rowClicked = (row: any) => {
    router.push({ name: "patients/payments", params: { id: row.patient_id } })
}

const formattedValue = (value: number) => {
    return formatNumber(value)
}

useEntryListUpdater(`/payments`, paymentsStore, async (response) => {
    paymentsStore.totalPayments = response.total_payments
    paymentsStore.totalRemainingPayments = response.total_remaining_payments
})
</script>
