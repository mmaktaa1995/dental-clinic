<template>
    <file-pond
        ref="pond"
        name="files"
        label-idle="اسحب وافلت الملفات هنا..."
        label-tap-to-cancel="اضغط هنا للإلغاء"
        label-tap-to-retry="اضغط هنا للإعادة"
        label-tap-to-undo="اضغط هنا للتراجع"
        label-file-processing="جاري رفع الملفات"
        label-file-processing-complete="تم رفع الملفات"
        :allow-multiple="true"
        accepted-file-types="image/jpeg, image/png"
        :server="serverConfig"
        chunk-uploads="true"
        :files="myFiles"
        @init="handleFilePondInit"
        @ended="handleFilePondEnd"
        @error="handleFilePondError"
    />
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue"
import vueFilePond from "vue-filepond"
import axios from "axios"
import "filepond/dist/filepond.min.css"
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFilePoster from "filepond-plugin-file-poster"

// Initialize FilePond component with plugins
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFilePoster, FilePondPluginImagePreview)

const files = defineModel < [] > { required: true }
// Props
const props = defineProps({
    folder: {
        type: String,
        default: "temp",
    },
    type: {
        type: String,
        default: "images",
    },
    files: {
        type: Array,
        default: () => [],
    },
})
const $emits = defineEmits(["updateFiles"])

// Refs
const pond = ref(null)
const myFiles = ref(props.files)

// Computed server config
const serverConfig = computed(() => ({
    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
        const formData = new FormData()
        formData.append(fieldName, file, file.name)
        formData.append("folder", props.folder)
        formData.append("type", props.type)

        const CancelToken = axios.CancelToken
        const source = CancelToken.source()

        axios
            .post(`/api/upload`, formData, {
                cancelToken: source.token,
                onUploadProgress: (e) => {
                    progress(e.lengthComputable, e.loaded, e.total)
                },
            })
            .then((response) => {
                if (Array.isArray(response.data)) {
                    response.data.forEach((path) => load(path))
                } else {
                    const file = {
                        source: response.data.path,
                        options: {
                            type: "local",
                            metadata: {
                                poster: response.data.path,
                            },
                        },
                    }
                    myFiles.value.push(file)
                    files.value.push(file)
                    emitFiles()
                    load(JSON.stringify(response.data))
                }
            })
            .catch((thrown) => {
                if (axios.isCancel(thrown)) {
                    console.log("Request canceled", thrown.message)
                } else {
                    error("Upload failed")
                }
            })

        return {
            abort: () => {
                source.cancel("Operation canceled by the user.")
                abort()
            },
        }
    },
    revert: (uniqueFileId, load, error) => {
        const data = JSON.parse(uniqueFileId).path.split("/")
        const name = data[data.length - 1]
        const folder = data[data.length - 2]

        axios
            .delete(`/api/upload/${folder}/${name}/${props.type}`, data)
            .then(({ data }) => {
                myFiles.value = myFiles.value.filter((image) => uniqueFileId.path !== image.source)
                emitFiles()
                console.log("File removed:", data.message)
            })
            .catch(error)

        load()
    },
    remove: (uniqueFileId, load, error) => {
        const data = uniqueFileId.split("/")
        const name = data[data.length - 1]
        const folder = data[data.length - 2]

        axios
            .delete(`/api/upload/${folder}/${name}/${props.type}`, data)
            .then(({ data }) => {
                myFiles.value = myFiles.value.filter((image) => uniqueFileId !== image.source)
                emitFiles()
                console.log("File removed:", data.message)
            })
            .catch(error)

        load()
    },
}))

// Methods
const emitFiles = () => {
    console.log(myFiles.value)
    $emits(
        "updateFiles",
        myFiles.value.map((file) => ({ image: file.source })),
    )
}

const handleFilePondInit = () => {
    console.log("FilePond has initialized")
}

const handleFilePondEnd = (data) => {
    console.log("FilePond has ended", data)
}

const handleFilePondError = (error) => {
    console.log("FilePond encountered an error", error)
}

watch(
    () => props.files,
    (newFiles) => {
        myFiles.value = newFiles
    },
    { immediate: true },
)
</script>

<style scoped></style>
