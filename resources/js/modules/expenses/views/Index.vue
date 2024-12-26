<template>
    <div>
        <CDataTable :columns="columns" :store="expensesStore" @row-clicked="rowClicked">
            <template #header>
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-lg">{{ $t("expenses.title") }}</div>
                    <CButton sm type="primary" :to="{ name: `expenses/general`, params: { id: -1 } }"> {{ $t("global.actions.create") }}</CButton>
                </div>
            </template>
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="expensesStore.query" class="w-100" :label="$t('expenses.name')" name="name"></CTextField>
                    <CDatePicker v-model="expensesStore.from_date" :label="$t('global.fromDate')" name="from_date"></CDatePicker>
                    <CDatePicker v-model="expensesStore.to_date" :label="$t('global.toDate')" name="to_date"></CDatePicker>
                </div>
            </template>
        </CDataTable>
        <CDetailPageOutlet :reload-list="reload" />
    </div>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { useI18n } from "vue-i18n"
import { formatNumber } from "@/logic/helpers.js"
import { useRouter } from "vue-router"
import DateTime from "@/components/Table/components/DateTime.vue"
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import CDetailPageOutlet from "@/components/CDetailPage/CDetailPageOutlet.vue"
import { useExpensesStore } from "@/modules/expenses/store"

const expensesStore = useExpensesStore()
const router = useRouter()
const { t } = useI18n()

const columns: DataTableColumn[] = [
    { field: "name", headerName: t("services.name") },
    {
        field: "amount",
        headerName: t("services.price"),
        valueFormatter: (value: any) => {
            return formatNumber(value)
        },
    },
    {
        field: "created_at",
        headerName: t("payments.date"),
        cellRenderer: DateTime,
    },
]

const rowClicked = (row: any) => {
    console.log(row)
    router.push({ name: "expenses/general", params: { id: row.id } })
}

const { reload } = useEntryListUpdater(`/expenses`, expensesStore)
</script>
