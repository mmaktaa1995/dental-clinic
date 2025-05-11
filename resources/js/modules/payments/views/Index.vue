<template>
    <div>
        <PaymentsTable :columns="columns" :store="paymentsStore" :row-clicked="rowClicked">
            <template #header>
                <div>
                    <div class="flex items-center justify-between">
                        <div class="font-semibold text-lg">{{ $t("payments.title") }}</div>
                        <CButton type="primary" sm @click="openAddPayment">{{ $t("global.actions.create") }}</CButton>
                    </div>
                    <!--                    <div class="mt-2">-->
                    <!--                        <label class="block text-sm font-medium text-gray-700 text-right ltr:text-left">{{ $t("payments.totalAmount") }}</label>-->
                    <!--                        <label class="block text-2xl font-medium text-green-600 text-right ltr:text-left">{{ formattedValue(paymentsStore.totalPayments) }}</label>-->
                    <!--                    </div>-->
                </div>
            </template>
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="paymentsStore.query" class="w-100" :label="$t('patients.name')" name="name"></CTextField>
                    <CDatePicker v-model="paymentsStore.from_date" :label="$t('global.fromDate')" name="from_date"></CDatePicker>
                    <CDatePicker v-model="paymentsStore.to_date" :label="$t('global.toDate')" name="to_date"></CDatePicker>
                </div>
            </template>
        </PaymentsTable>
        <AddPayment v-model="paymentDetailsStore.isAddPaymentModalOpened" :is-edit="paymentDetailsStore.isEdit" :reload="reload" :payment="paymentDetailsStore.payment" :patient="paymentDetailsStore.patient"></AddPayment>
        <CConfirmModal v-model="paymentDetailsStore.isDeletePaymentModalOpened" :confirm-title="$t('payments.deletePayment')" :confirm-body-message="$t('payments.deletePaymentConfirmation')" :loading="isDeleting" @confirm-callback="deletePayment"></CConfirmModal>
    </div>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { useI18n } from "vue-i18n"
import PaymentsTable from "@/modules/payments/components/PaymentsTable.vue"
import { formatNumber } from "@/logic/helpers.js"
import PaymentStatus from "@/modules/payments/components/table/PaymentStatus.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { PaymentEntry, usePaymentsStore } from "@/modules/payments/store"
import AddPayment from "@/modules/payments/components/AddPayment.vue"
import CDate from "@/components/Table/components/CDate.vue"
import { usePaymentDetailsStore } from "@/modules/payments/detailStore"
import { ref } from "vue"
import { api } from "@/logic/api"
import PaymentActions from "@/modules/payments/components/table/PaymentActions.vue"

const paymentsStore = usePaymentsStore()
const paymentDetailsStore = usePaymentDetailsStore()
const isDeleting = ref(false)
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
    // {
    //     field: "remaining_amount",
    //     headerName: t("payments.remainingAmount"),
    //     textClass: "!text-rose-600",
    //     valueFormatter: (value: any) => {
    //         return formatNumber(value)
    //     },
    // },
    { field: "status", headerName: t("payments.status"), cellRenderer: PaymentStatus },
    { field: "date", headerName: t("payments.paymentDate"), cellRenderer: CDate },
    { field: "created_at", headerName: t("patients.createdAt"), cellRenderer: DateTime },
    { field: "action", headerName: "", cellRenderer: PaymentActions },
]

const rowClicked = (row: PaymentEntry) => {
    paymentDetailsStore.patient = row.patient
    paymentDetailsStore.payment = row
    paymentDetailsStore.isEdit = true
    paymentDetailsStore.isAddPaymentModalOpened = true
}

const openAddPayment = () => {
    paymentDetailsStore.isEdit = false
    paymentDetailsStore.patient = undefined
    paymentDetailsStore.payment = undefined
    paymentDetailsStore.isAddPaymentModalOpened = true
}

const formattedValue = (value: number) => {
    return formatNumber(value)
}

const deletePayment = () => {
    if (isDeleting.value) {
        return
    }
    isDeleting.value = true
    api.delete(`/payments/${paymentDetailsStore.entryId}`)
        .then(() => {
            reload()
            paymentDetailsStore.isDeletePaymentModalOpened = false
            paymentDetailsStore.entryId = -1
        })
        .finally(() => {
            isDeleting.value = false
        })
}

const { reload } = useEntryListUpdater(`/payments`, paymentsStore, async (response) => {
    paymentsStore.totalPayments = response.total_payments
    paymentsStore.totalRemainingPayments = response.total_remaining_payments
})
</script>
