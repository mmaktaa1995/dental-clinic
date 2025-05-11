<template>
    <c-container class="col-span-full">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("patients.visits") }}</h3>
            <!--            <p class="text-sm text-gray-500">{{ $t("patients.visitsDescription") }}</p>-->
        </div>
        <CDataTable :store="patientVisitsStore" :columns="columns" :row-clickable="false"></CDataTable>
    </c-container>
</template>

<script setup lang="ts">
import { usePatientDetailsStore } from "@/modules/patients/detailStore"
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { useI18n } from "vue-i18n"
import { usePatientVisitsStore } from "@/modules/patients/visitsStore"
import CDate from "@/components/Table/components/CDate.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { formatNumber } from "@/logic/helpers"

const patientDetailsStore = usePatientDetailsStore()
const patientVisitsStore = usePatientVisitsStore()

useEntryListUpdater(`/patients/${patientDetailsStore.entryId}/visits`, patientVisitsStore, () => {
    patientDetailsStore.subPages.visits.title = t("patients.visits") + ` (${patientVisitsStore.pagination.total})`
})

const { t } = useI18n()

const columns = [
    { field: "notes", headerName: t("payments.action") },
    {
        field: "amount",
        headerName: t("payments.amount"),
        textClass: "!text-green-600",
        valueFormatter: (value: any) => {
            return formatNumber(value)
        },
    },
    { field: "date", headerName: t("payments.date"), cellRenderer: CDate },
    { field: "created_at", headerName: t("patients.createdAt"), cellRenderer: DateTime },
]
</script>

<style scoped></style>
