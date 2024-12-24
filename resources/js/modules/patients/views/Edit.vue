<template>
    <c-container>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("patients.patientInfo") }}</h3>
            <p class="text-sm text-gray-500">{{ $t("patients.patientInfoDescription") }}</p>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <CTextField v-model="patientDetailsStore.entry.file_number" :disabled="true" :label="$t('patients.fileNumber')" :errors="patientDetailsStore.errors" name="file_number"></CTextField>
            <CTextField v-model="patientDetailsStore.entry.name" :label="$t('patients.name')" :errors="patientDetailsStore.errors" name="name"></CTextField>
            <CTextField v-model="patientDetailsStore.entry.age" :label="$t('patients.age')" type="number" :errors="patientDetailsStore.errors" name="age"></CTextField>
            <CSelect v-model="patientDetailsStore.entry.gender" :options="genders" :label="$t('patients.gender')" :errors="patientDetailsStore.errors" name="gender"></CSelect>
        </div>

        <hr class="my-6" />

        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("patients.patientContactInfo") }}</h3>
            <p class="text-sm text-gray-500">{{ $t("patients.patientContactInfoDescription") }}</p>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <CTextField v-model="patientDetailsStore.entry.phone" label="رقم الهاتف" type="tel" :errors="patientDetailsStore.errors" name="phone"></CTextField>
            <CTextField v-model="patientDetailsStore.entry.mobile" label="رقم الموبايل" type="tel" :errors="patientDetailsStore.errors" name="mobile"></CTextField>
        </div>
    </c-container>
</template>

<script setup>
import { usePatientDetailStore } from "@/modules/patients/detailStore.ts"
import { useI18n } from "vue-i18n"

const patientDetailsStore = usePatientDetailStore()
const { t } = useI18n()

const genders = [
    {
        id: 1,
        label: t("global.genders.male"),
    },
    {
        id: 2,
        label: t("global.genders.female"),
    },
]
</script>
