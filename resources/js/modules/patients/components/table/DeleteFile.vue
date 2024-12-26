<template>
    <div class="" @click="openDeleteFile">
        <c-icon-delete size="5" class="hover:text-pink-600"></c-icon-delete>
    </div>
</template>

<script setup lang="ts">
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { usePatientFilesStore } from "@/modules/patients/filesStore"

const props = defineProps<{
    value: any
    entry: Record<string, any>
    column: DataTableColumn
}>()
const patientFilesStore = usePatientFilesStore()

const openDeleteFile = () => {
    patientFilesStore.isDeletingFileModalOpened = true
    patientFilesStore.fileToDelete = props.entry.id
}
</script>
