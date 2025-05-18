<template>
    <div class="flex flex-wrap gap-2">
        <Delete :value :column :entry></Delete>
        <AddDebt :value :column :entry></AddDebt>
    </div>
</template>
<script setup lang="ts">
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { PaymentEntry } from "@/modules/payments/store"
import AddDebt from "@/modules/payments/components/table/AddDebt.vue"
import Delete from "@/modules/payments/components/table/Delete.vue"

defineProps<{
    value: any
    entry: PaymentEntry
    column: DataTableColumn
}>()
</script>
