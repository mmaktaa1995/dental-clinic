<template>
    <c-container class="col-span-full">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("patients.relatedPatientFiles") }}</h3>
            <p class="text-sm text-gray-500">{{ $t("patients.relatedPatientFilesDescription") }}</p>
        </div>
        <c-file-pond-component v-model="files" :accepted-file-types="['image/jpeg', 'image/png']" folder="patients" type="files" @file-uploaded="fileUploaded" />
    </c-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue"
import axios from "axios"
import { usePatientDetailStore } from "@/modules/patients/detailStore"

const files = ref([])
const submitted = ref(false)

type UploadedFile = { file: string; type: string }
const fileUploaded = (file: UploadedFile) => {
    files.value.push(file)
    if (files.value.length) {
        update()
    }
}

const update = () => {
    submitted.value = true
    axios
        .patch(`/api/patients/${patientDetailStore.entryId}/files`, { files: files.value })
        .then(({ data }) => {
            // bus.$emit("flash-message", { text: data.message, type: "success" })
        })
        .catch((error) => {
            // bus.$emit("flash-message", { text: error.response.message, type: "danger" })
        })
        .finally(() => {
            submitted.value = false
        })
}

const patientDetailStore = usePatientDetailStore()

onMounted(() => {
    files.value = patientDetailStore.entry.files.map((file) => ({
        source: file.file,
        options: {
            type: "local",
            metadata: {
                poster: file.file,
            },
        },
    }))
})
</script>

<style scoped></style>
