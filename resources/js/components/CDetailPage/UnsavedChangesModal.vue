<template>
    <CDialog v-model="isUnsavedChangesWarningOpen" type="warning" show-icon>
        <template #header>
            {{ $t("cDetailPage.unsavedChangesWarnings.title") }}
        </template>
        <template #body>
            {{ $t("cDetailPage.unsavedChangesWarnings.discardUnsavedChanges") }}
        </template>
        <template #actions>
            <CButton type="primary" @click="discardChanges">
                {{ $t("cDetailPage.unsavedChangesWarnings.discardChanges") }}
            </CButton>
            <CButton @click="closeUnsavedChangesWarning">{{ $t("global.actions.cancel") }}</CButton>
        </template>
    </CDialog>
</template>

<script setup lang="ts">
import { computed } from "vue"

const props = defineProps<{
    modelValue: boolean
    closeUnsavedChangesWarning: () => void
    discardChanges: () => void
}>()
const emit = defineEmits(["update:modelValue"])

const isUnsavedChangesWarningOpen = computed({
    get() {
        return props.modelValue
    },
    set(newValue: boolean) {
        emit("update:modelValue", newValue)
    },
})
</script>
