<template>
    <div class="flex items-center">
        <div :class="['flex justify-center items-center w-9 h-9 rounded-full bg-opacity-15', backgroundColor]">
            <i :class="iconClass" class="text-xl"></i>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { DataTableColumn } from "@/components/Table/DataTable.vue"

const props = defineProps<{
    value: any
    entry: Record<string, any>
    column: DataTableColumn
}>()

// File type mappings
const fileTypeIcons = {
    image: { icon: "far fa-file-image text-pink-300", color: "bg-pink-500" },
    video: { icon: "far fa-file-video text-green-300", color: "bg-green-500" },
    audio: { icon: "far fa-file-audio text-purple-300", color: "bg-purple-500" },
    pdf: { icon: "far fa-file-pdf text-red-300", color: "bg-red-500" },
    word: { icon: "far fa-file-word text-blue-300", color: "bg-blue-600" },
    excel: { icon: "far fa-file-excel text-teal-300", color: "bg-teal-600" },
    powerpoint: { icon: "far fa-file-powerpoint text-orange-300", color: "bg-orange-500" },
    text: { icon: "far fa-file-alt text-gray-300", color: "bg-gray-500" },
    archive: { icon: "far fa-file-archive text-yellow-300", color: "bg-yellow-500" },
    default: { icon: "far fa-file text-gray-300", color: "bg-gray-400" },
}

// Computed properties
const fileType = computed(() => {
    if (props.entry.type.startsWith("image/")) return "image"
    if (props.entry.type.startsWith("video/")) return "video"
    if (props.entry.type.startsWith("audio/")) return "audio"
    if (props.entry.type === "application/pdf") return "pdf"
    if (props.entry.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || props.entry.type === "application/msword") return "word"
    if (props.entry.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || props.entry.type === "application/vnd.ms-excel") return "excel"
    if (props.entry.type === "application/vnd.openxmlformats-officedocument.presentationml.presentation" || props.entry.type === "application/vnd.ms-powerpoint") return "powerpoint"
    if (props.entry.type.startsWith("text/")) return "text"
    if (props.entry.type === "application/zip" || props.entry.type === "application/x-rar-compressed") return "archive"
    return "default"
})

const iconClass = computed(() => fileTypeIcons[fileType.value]?.icon || fileTypeIcons["default"].icon)
const backgroundColor = computed(() => fileTypeIcons[fileType.value]?.color || fileTypeIcons["default"].color)
</script>
