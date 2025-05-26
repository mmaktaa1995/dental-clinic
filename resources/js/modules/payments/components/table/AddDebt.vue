<template>
    <div v-if="entry.remaining_amount > 0" class="z-50">
        <c-icon class="text-teal-500 transition hover:bg-gray-100 rounded-full p-3 cursor-pointer" @click="openAddDebtPayment">fas fa-money-bill</c-icon>
    </div>
</template>
<script setup lang="ts">
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { PaymentEntry } from "@/modules/payments/store"
import { usePaymentDetailsStore } from "@/modules/payments/detailStore"

const props = defineProps<{
    value: any
    entry: PaymentEntry
    column: DataTableColumn
}>()

const paymentDetailsStore = usePaymentDetailsStore()

const openAddDebtPayment = (event: MouseEvent) => {
    event.stopPropagation()
    paymentDetailsStore.isEdit = false
    paymentDetailsStore.patient = props.entry.patient
    paymentDetailsStore.payment = props.entry
    paymentDetailsStore.isAddPaymentModalOpened = true
}
</script>
