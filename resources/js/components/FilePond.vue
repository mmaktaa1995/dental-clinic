<template>
    <file-pond
        ref="pond"
        name="files"
        :disabled="disabled"
        :max-file-size="props.maxFileSize"
        :label-idle="$t('filePond.idle')"
        :label-tap-to-cancel="$t('filePond.cancelLabel')"
        :label-tap-to-retry="$t('filePond.retryLabel')"
        :label-tap-to-undo="$t('filePond.undoLabel')"
        :label-invalid-field="$t('filePond.invalidFile')"
        :label-file-load-error="$t('filePond.fileLoadError')"
        :label-file-loading="$t('filePond.loading')"
        label-file-processing=""
        :label-file-processing-aborted="$t('filePond.uploadCancelled')"
        label-file-processing-complete="100%"
        :label-file-processing-error="$t('filePond.uploadFailed')"
        :label-file-processing-revert-error="$t('filePond.uploadCancelledError')"
        :label-file-remove-error="$t('filePond.deleteFile')"
        :label-file-size-not-available="$t('filePond.fileInformationUnavailable')"
        :label-file-type-not-allowed="$t('filePond.fileTypeUnsupported')"
        :label-file-waiting-for-size="$t('filePond.waitForFileInformation')"
        :allow-multiple="true"
        :accepted-file-types="acceptedFileTypes"
        :server="serverConfig"
        chunk-uploads="true"
        :files="myFiles"
        @processfiles="fileUploaded"
        @ended="fileUploaded"
        @addfile="fileAdded"
        @removefile="fileRemoved"
    />
</template>

<script setup lang="ts">
import { ref, computed } from "vue"
import vueFilePond from "vue-filepond"
import "filepond/dist/filepond.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFilePoster from "filepond-plugin-file-poster"
import { FilePondErrorDescription, FilePondFile } from "filepond"
import { api } from "@/logic/api"

// Initialize FilePond component with plugins
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFilePoster)
const myFiles = defineModel<any[]>({ required: true })
const loading = defineModel<boolean>("loading")

const props = withDefaults(
    defineProps<{
        folder?: string
        type?: string
        acceptedFileTypes?: string[]
        maxFileSize?: string
        disabled?: boolean
    }>(),
    {
        folder: "temp",
        type: "images",
        acceptedFileTypes: undefined,
        maxFileSize: undefined,
        disabled: undefined,
    },
)

const $emits = defineEmits<{
    (e: "fileUploaded", serverId: string, file: FilePondFile): void
    (e: "fileAdded", file: FilePondFile): void
    (e: "fileRemoved", file: FilePondFile): void
    (e: "error"): void
}>()

// Refs
const pond = ref(null)

// Computed server config
const defaultHeaders = api.getDefaultHeaders()
delete defaultHeaders["Content-Type"]
const serverConfig = computed(() => ({
    process: {
        url: `/api/v1/upload`,
        headers: (file: File) => {
            if (file.size <= 0 && pond.value) {
                pond.value.removeFile(file)
            }
            return { ...defaultHeaders, "Upload-Name": file.name }
        },
        ondata: (formData: FormData) => {
            formData.append("folder", props.folder)
            formData.append("type", props.type)
            return formData
        },
        withCredentials: true,
    },
    revert: {
        url: `/api/v1/upload/${props.folder}/${props.type}`,
        headers: defaultHeaders,
        withCredentials: true,
    },
    remove: {
        url: `/api/v1/upload/${props.folder}/${props.type}`,
        headers: defaultHeaders,
        withCredentials: true,
    },
}))

const fileAdded = (_: FilePondErrorDescription, file: FilePondFile) => {
    loading.value = true
    $emits("fileAdded", file)
}

const fileRemoved = (_: FilePondErrorDescription, file: FilePondFile) => {
    $emits("fileRemoved", file)
}

const fileUploaded = () => {
    const file: FilePondFile = pond.value!.getFiles()[0]
    const filesUploaded = JSON.parse(file.serverId)
    filesUploaded?.data?.forEach((uploadedFile) => {
        $emits("fileUploaded", uploadedFile, file)
    })
    loading.value = false
}
</script>

<style scoped></style>
