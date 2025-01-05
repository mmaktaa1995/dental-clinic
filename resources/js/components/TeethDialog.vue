<template>
    <!-- eslint-disable vue/no-mutating-props -->
    <CDialog v-model="isOpen" @confirm-callback="submitSelectedTeeth">
        <template #header>
            {{ $t("patients.selectAffectedTeeth") }}
        </template>
        <template #body>
            <div>
                <div v-for="colorSpecification in colorsSpecification" :key="colorSpecification.color" class="flex items-center gap-2 mb-1">
                    <span class="rounded w-4 h-4 border shadow" :style="{ background: colorSpecification.color }"></span>
                    <span class="text-sm text-gray-900">{{ colorSpecification.label }}</span>
                </div>
            </div>
            <div class="flex justify-center">
                <Teeth v-model="selectedTeeth" v-model:treated-teeth="treatedTeeth" :treat-mode></Teeth>
            </div>
        </template>
    </CDialog>
</template>
<script setup lang="ts">
import Teeth from "@/components/Teeth.vue"
import { useI18n } from "vue-i18n"

const isOpen = defineModel<boolean>({ required: true })
const selectedTeeth = defineModel<Record<any, any>>("teeth", { required: true })
const treatedTeeth = defineModel<Record<any, any>>("treatedTeeth", { required: false, default: {} })

withDefaults(
    defineProps<{
        treatMode?: boolean
    }>(),
    {
        treatMode: false,
    },
)
const $emit = defineEmits(["teethSelected"])

const submitSelectedTeeth = () => {
    $emit("teethSelected")
}
const { t } = useI18n()

const colorsSpecification = [
    { color: "rgba(0, 255, 0, 1)", label: t("patients.treatedTooth") },
    { color: "rgba(255, 0, 0, 0.85)", label: t("patients.affectedTooth") },
    { color: "rgba(255, 255, 255, 0.85)", label: t("patients.normalTooth") },
]
</script>

<style scoped></style>
