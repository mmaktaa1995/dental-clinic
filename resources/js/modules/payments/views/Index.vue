<template>
    <c-container>
        <PaymentsTable :columns="columns" :store="patientPaymentsStore" :row-clicked="rowClicked">
            <template #header>{{ $t("payments.title") }}</template>
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="patientPaymentsStore.query" class="w-100" :label="$t('patients.name')" name="name"></CTextField>
                    <CDatePicker v-model="patientPaymentsStore.from_date" :label="$t('global.fromDate')" name="from_date"></CDatePicker>
                    <CDatePicker v-model="patientPaymentsStore.to_date" :label="$t('global.toDate')" name="to_date"></CDatePicker>
                </div>
            </template>
        </PaymentsTable>
    </c-container>
</template>

<script setup lang="ts">
import { usePatientPaymentsStore } from "@/modules/patients/paymentsStore"
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { useI18n } from "vue-i18n"
import PaymentsTable from "@/modules/payments/components/PaymentsTable.vue"
import { formatNumber } from "@/logic/helpers.js"
import { useRouter } from "vue-router"
import PaymentStatus from "@/modules/payments/components/table/PaymentStatus.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { DataTableColumn } from "@/components/Table/DataTable.vue"

const patientPaymentsStore = usePatientPaymentsStore()
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

useEntryListUpdater(`/payments`, patientPaymentsStore, async () => {
    patientPaymentsStore.totalPayments = patientPaymentsStore.entries!.reduce((sum, payment) => sum + +payment.amount, 0)
    patientPaymentsStore.totalRemainingPayments = patientPaymentsStore.entries!.reduce((sum, payment) => sum + +payment.remaining_amount, 0)
})
</script>
