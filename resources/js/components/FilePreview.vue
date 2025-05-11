<template>
    <div v-if="fileUrl" class="file-preview">
        <CPureDialog v-model="isFilePreviewOpened">
            <template v-if="isImage">
                <img :src="fileUrl" alt="Preview" class="w-full h-auto" />
            </template>
            <template v-else-if="isAudio">
                <audio controls class="w-full">
                    <source :src="fileUrl" :type="mimeType" />
                    Your browser does not support the audio element.
                </audio>
            </template>
            <template v-else-if="isVideo">
                <video controls class="w-full h-auto">
                    <source :src="fileUrl" :type="mimeType" />
                    Your browser does not support the video tag.
                </video>
            </template>
            <template v-else-if="isPdf">
                <iframe :src="fileUrl" class="w-[80vw] h-[90vh] mx-auto" frameborder="0"></iframe>
            </template>
            <template v-else-if="isOffice">
                <iframe :src="officePreviewUrl" class="w-[80vw] h-[90vh] mx-auto" frameborder="0"></iframe>
            </template>
            <template v-else>
                <p>Cannot preview this file type.</p>
            </template>
        </CPureDialog>
    </div>
</template>
<script setup lang="ts">
import { computed, ref } from "vue"

// Props for the component
const props = defineProps({
    fileUrl: {
        type: String,
        required: true,
    },
    mimeType: {
        type: String,
        required: true,
    },
})

const isFilePreviewOpened = defineModel<boolean>({ required: true })

// File type checks
const isImage = computed(() => ["image/png", "image/jpeg", "image/gif", "image/webp", "image/svg+xml"].includes(props.mimeType))

const isAudio = computed(() => ["audio/mpeg", "audio/wav", "audio/ogg", "audio/mp4"].includes(props.mimeType))

const isVideo = computed(() => ["video/mp4", "video/webm", "video/ogg"].includes(props.mimeType))

const isPdf = computed(() => props.mimeType === "application/pdf")

// Office file preview using Google Docs viewer
const isOffice = computed(() =>
    [
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "application/vnd.ms-excel",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "application/vnd.ms-powerpoint",
        "application/vnd.openxmlformats-officedocument.presentationml.presentation",
    ].includes(props.mimeType),
)

const officePreviewUrl = computed(() => `https://view.officeapps.live.com/op/view.aspx?src=${encodeURIComponent(props.fileUrl)}`)
</script>

<style scoped>
.file-preview img {
    max-height: 300px;
    object-fit: contain;
}
.file-preview iframe {
    border: none;
}
</style>
