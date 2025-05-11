<template>
    <c-container>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("expenses.expenseInfo") }}</h3>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <CTextField v-model="expenseDetailsStore.entry.name" :label="$t('expenses.name')" type="text" :errors="expenseDetailsStore.errors" name="name"></CTextField>
            <CTextField v-model="expenseDetailsStore.entry.amount" :label="$t('expenses.amount')" type="number" :errors="expenseDetailsStore.errors" name="amount"></CTextField>
            <CDatePicker v-model="expenseDetailsStore.entry.date" :label="$t('expenses.date')" :errors="expenseDetailsStore.errors" name="date"></CDatePicker>
            <CTextArea v-model="expenseDetailsStore.entry.description" :label="$t('expenses.description')" :errors="expenseDetailsStore.errors" name="description" class="col-span-full"></CTextArea>
        </div>
    </c-container>
</template>

<script setup>
import { useExpenseDetailsStore } from "@/modules/expenses/detailStore"

const expenseDetailsStore = useExpenseDetailsStore()
</script>
