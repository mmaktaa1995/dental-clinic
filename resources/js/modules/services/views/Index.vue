<template>
    <c-container>
        <CDataTable :columns="columns" :store="servicesStore" @row-clicked="rowClicked">
            <template #header>
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-lg">{{ $t("services.title") }}</div>
                    <CButton type="primary" :to="{ name: `services/general`, params: { id: -1 } }"> {{ $t("global.actions.create") }}</CButton>
                </div>
            </template>
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="servicesStore.query" class="w-100" :label="$t('patients.name')" name="name"></CTextField>
                </div>
            </template>
        </CDataTable>
        <CDetailPageOutlet :reload-list="reload" />
    </c-container>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { useI18n } from "vue-i18n"
import { formatNumber } from "@/logic/helpers.js"
import { useRouter } from "vue-router"
import DateTime from "@/components/Table/components/DateTime.vue"
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { useServicesStore } from "@/modules/services/store"
import CDetailPageOutlet from "@/components/CDetailPage/CDetailPageOutlet.vue"

const servicesStore = useServicesStore()
const router = useRouter()
const { t } = useI18n()

const columns: DataTableColumn[] = [
    { field: "name", headerName: t("services.name") },
    {
        field: "price",
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
    router.push({ name: "services/general", params: { id: row.id } })
}

const { reload } = useEntryListUpdater(`/services`, servicesStore)
</script>
