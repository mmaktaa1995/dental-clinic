<template>
    <div class="" @click="openDeleteFile">
        <c-icon class="text-red-500 transition hover:bg-gray-100 rounded-full p-3 cursor-pointer">far fa-trash-alt</c-icon>
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
