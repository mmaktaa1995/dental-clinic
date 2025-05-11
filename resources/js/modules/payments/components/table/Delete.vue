<template>
    <div>
        <c-icon class="text-red-500 transition hover:bg-gray-100 rounded-full p-3 cursor-pointer" @click="openDeletePaymentModal">far fa-trash-alt</c-icon>
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

const openDeletePaymentModal = (event) => {
    event.stopPropagation()
    paymentDetailsStore.isDeletePaymentModalOpened = true
    paymentDetailsStore.entryId = props.entry.id
}
</script>
