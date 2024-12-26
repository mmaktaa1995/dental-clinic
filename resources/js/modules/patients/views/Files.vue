<template>
    <c-container class="col-span-full">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("patients.relatedPatientFiles") }}</h3>
            <p class="text-sm text-gray-500">{{ $t("patients.relatedPatientFilesDescription") }}</p>
        </div>
        <c-file-pond-component v-model="files" :accepted-file-types="['image/jpeg', 'image/png', 'application/pdf']" folder="patients" type="files" @file-uploaded="fileUploaded" />
        <CDataTable :store="patientFilesStore" :columns="columns"></CDataTable>
        <teleport to=".modal-teleport">
            <CConfirmModal
                v-model="patientFilesStore.isDeletingFileModalOpened"
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
        </teleport>
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
import DeleteFile from "@/modules/patients/components/table/DeleteFile.vue"

const files = ref<any[]>([])
const isSaving = ref(false)
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
        cellRenderer: DeleteFile,
    },
]

const update = async () => {
    if (isSaving.value) {
        return
    }

    isSaving.value = true
    const filesToUpload = [...files.value]
    await api
        .patch(`/patients/${patientDetailsStore.entryId}/files`, { files: filesToUpload })
        .then(() => {
            // bus.$emit("flash-message", { text: data.message, type: "success" })
            files.value = files.value.filter((file) => {
                return !filesToUpload.find((uploadedFile) => uploadedFile.file === file.file)
            })
            reload()
        })
        .catch((error) => {
            // bus.$emit("flash-message", { text: error.response.message, type: "danger" })
        })
        .finally(() => {
            isSaving.value = false
        })
}

const deleteFile = () => {
    if (isDeleting.value) {
        return
    }

    isDeleting.value = true
    api.delete(`/patients/${patientDetailsStore.entryId}/files/${patientFilesStore.fileToDelete}`)
        .then(() => {
            isDeleting.value = false
            patientFilesStore.fileToDelete = null
            patientFilesStore.isDeletingFileModalOpened = false
            reload()
        })
        .catch(() => {
            isDeleting.value = false
        })
}
</script>

<style scoped></style>
