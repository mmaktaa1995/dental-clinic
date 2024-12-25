<template>
    <file-pond
        ref="pond"
        name="files"
        :disabled="disabled"
        :max-file-size="props.maxFileSize"
        label-idle="اسحب وافلت الملفات هنا..."
        label-tap-to-cancel="اضغط هنا للإلغاء"
        label-tap-to-retry="اضغط هنا للإعادة"
        label-tap-to-undo="اضغط هنا للتراجع"
        label-file-processing="جاري رفع الملفات"
        label-file-processing-complete="تم رفع الملفات"
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
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFilePoster from "filepond-plugin-file-poster"
import { FilePondErrorDescription, FilePondFile } from "filepond"
import { api } from "@/logic/api"

// Initialize FilePond component with plugins
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFilePoster)
// const pond = ref()
const myFiles = defineModel<any[]>({ required: true })
// Props
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
        url: `/api/upload`,
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
        url: `/api/upload/${props.folder}/${props.type}`,
        headers: defaultHeaders,
        withCredentials: true,
    },
    remove: {
        url: `/api/upload/${props.folder}/${props.type}`,
        headers: defaultHeaders,
        withCredentials: true,
    },
}))

// Methods
const emitFiles = () => {
    // console.log(myFiles.value)
    // $emits(
    //     "updateFiles",
    //     myFiles.value.map((file) => ({ image: file.source })),
    // )
}

const fileAdded = (_: FilePondErrorDescription, file: FilePondFile) => {
    console.log(file)
    // After a file was added, we decide if we want to upload it to our servers or upload it to bunny.net
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
}

// watch(
//     () => props.files,
//     (newFiles) => {
//         myFiles.value = newFiles
//     },
//     { immediate: true },
// )
</script>

<style scoped></style>
