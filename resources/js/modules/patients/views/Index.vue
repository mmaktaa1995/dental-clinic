<template>
    <div>
        <CDataTable :store="patientsStore" :columns="columns" @row-clicked="openPatientDetails">
            <template #header>
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-lg">{{ $t("patients.moduleName") }}</div>
                    <div class="flex gap-2">
                        <ImportExportButtons 
                            model-type="patients" 
                            :filters="exportFilters"
                            @import-complete="handleImportComplete"
                        />
                        <div class="w-1 h-full bg-gray-200"></div>
                        <CButton sm type="primary" :to="{ name: `patients/general`, params: { id: -1 } }"> {{ $t("global.actions.create") }}</CButton>
                    </div>
                </div>
            </template>
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="patientsStore.query" class="w-100" :label="$t('patients.name')" name="name"></CTextField>
                    <CTextField v-model="patientsStore.file_number" class="w-100" :label="$t('patients.fileNumber')" name="file_number"></CTextField>
                    <CDatePicker v-model="patientsStore.from_date" :label="$t('global.fromDate')" name="from_date"></CDatePicker>
                    <CDatePicker v-model="patientsStore.to_date" :label="$t('global.toDate')" name="to_date"></CDatePicker>
                </div>
            </template>
        </CDataTable>
        <CDetailPageOutlet :reload-list="reload" />
    </div>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { usePatientsStore } from "@/modules/patients/store"
import { useRouter } from "vue-router"
import CDetailPageOutlet from "@/components/CDetailPage/CDetailPageOutlet.vue"
import { useI18n } from "vue-i18n"
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { useRouteQueryParam } from "@/logic/routeQuerySync"
import { storeToRefs } from "pinia"
import ImportExportButtons from "@/components/ImportExportButtons.vue"
import { computed } from "vue"
import { useToastStore } from "@/modules/global/toastStore"

const patientsStore = usePatientsStore()
const router = useRouter()
const { t } = useI18n()
const { file_number } = storeToRefs(patientsStore)
const toastStore = useToastStore()

useRouteQueryParam("file_number", undefined, "string", { targetRef: file_number })

const { reload } = useEntryListUpdater("/patients", patientsStore)

const columns: DataTableColumn[] = [
    { field: "name", headerName: t("patients.name") },
    { field: "file_number", headerName: t("patients.fileNumber") },
    { field: "mobile", headerName: t("patients.mobile"), sortable: false },
    { field: "created_at", headerName: t("patients.createdAt"), cellRenderer: DateTime },
]

const openPatientDetails = (rowData) => {
    router.push({ name: "patients/general", params: { id: rowData.id } })
}

// Computed property to provide current filters to export component
const exportFilters = computed(() => {
    return {
        name: patientsStore.query,
        file_number: patientsStore.file_number,
        from_date: patientsStore.from_date,
        to_date: patientsStore.to_date,
        gender: patientsStore.gender
    }
})

// Handle import completion
const handleImportComplete = (result: { success: boolean; message: string }) => {
    if (result.success) {
        toastStore.success(result.message)
        // Refresh the patients after import
        reload()
    } else {
        toastStore.error(result.message)
    }
}
</script>
