<template>
    <c-container class="col-span-full">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("patients.relatedPatientFiles") }}</h3>
            <p class="text-sm text-gray-500">{{ $t("patients.relatedPatientFilesDescription") }}</p>
        </div>
        <c-file-pond-component v-model="files" :accepted-file-types="['image/jpeg', 'image/png', 'application/pdf']" folder="patients" type="files" @file-uploaded="fileUploaded" />
        <CDataTable :store="patientFilesStore" :columns="columns"></CDataTable>
        <CConfirmModal
            v-model="isFileDeleteModalOpened"
            v-model:loading="isDeleting"
            :confirm-title="$t('global.deleteEntryTitle', { type: $t('filePond.file') })"
            :confirm-body-message="
                $t('global.deleteEntryBodyMessage', {
                    type: $t('filePond.file'),
                })
            "
            @confirm-callback="deleteFile"
        >
        </CConfirmModal>
    </c-container>
</template>

<script setup lang="ts">
import { ref } from "vue"
import { usePatientDetailsStore } from "@/modules/patients/detailStore"
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { usePatientFilesStore } from "@/modules/patients/filesStore"
import { useI18n } from "vue-i18n"
import FileType from "@/modules/patients/components/table/FileType.vue"
import { api } from "@/logic/api"

const files = ref<any[]>([])
const submitted = ref(false)
const isFileDeleteModalOpened = ref(false)
const isDeleting = ref(false)
const patientDetailsStore = usePatientDetailsStore()
const patientFilesStore = usePatientFilesStore()

type UploadedFile = { file: string; type: string }

const { reload } = useEntryListUpdater(`/patients/${patientDetailsStore.entryId}/files`, patientFilesStore, () => {
    patientDetailsStore.subPages.files.title = t("patients.files") + ` (${patientFilesStore.pagination.total})`
})

const fileUploaded = async (serverId: UploadedFile) => {
    // patientDetailStore.filesToSave.push(serverId)
    files.value.push(serverId)
    if (files.value.length) {
        await update()
    }
}
const { t } = useI18n()

const columns = [
    { field: "type", headerName: "", cellRenderer: FileType },
    { field: "file_name", headerName: t("filePond.file") },
    { field: "created_at", headerName: t("patients.createdAt") },
    {
        field: "action",
        headerName: "",
        cellRenderer: (rowData: any) => {
            return `<c-icon-trash>${rowData.id}</c-icon-trash>`
        },
    },
]

const update = async () => {
    submitted.value = true
    const filesToUpload = [...files.value]
    await api
        .patch(`/patients/${patientDetailsStore.entryId}/files`, { files: filesToUpload })
        .then(() => {
            // bus.$emit("flash-message", { text: data.message, type: "success" })
            console.log(filesToUpload)
            files.value = files.value.filter((file) => {
                return filesToUpload.find((uploadedFile) => uploadedFile.path === file.path)
            })
            reload()
        })
        .catch((error) => {
            // bus.$emit("flash-message", { text: error.response.message, type: "danger" })
        })
        .finally(() => {
            submitted.value = false
        })
}

const deleteFile = () => {
    isDeleting.value = true
    api.delete(`/patients/${patientDetailsStore.entryId}`)
        .then(() => {
            isDeleting.value = false
            reload()
        })
        .catch(() => {
            isDeleting.value = false
        })
}
</script>

<style scoped></style>
