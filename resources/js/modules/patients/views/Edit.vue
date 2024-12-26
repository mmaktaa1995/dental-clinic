<template>
    <c-container>
        <CAccordion :title="$t('patients.patientInfo')" :description="$t('patients.patientInfoDescription')">
            <div class="grid grid-cols-2 gap-6">
                <CTextField v-model="patientDetailsStore.entry.file_number" :disabled="true" :label="$t('patients.fileNumber')" :errors="patientDetailsStore.errors" name="file_number"></CTextField>
                <CTextField v-model="patientDetailsStore.entry.name" :label="$t('patients.name')" :errors="patientDetailsStore.errors" name="name"></CTextField>
                <CTextField v-model="patientDetailsStore.entry.age" :label="$t('patients.age')" type="number" :errors="patientDetailsStore.errors" name="age"></CTextField>
                <CSelect v-model="patientDetailsStore.entry.gender" :options="genders" :label="$t('patients.gender')" :errors="patientDetailsStore.errors" name="gender"></CSelect>
            </div>
        </CAccordion>

        <CAccordion :title="$t('patients.patientContactInfo')" :description="$t('patients.patientContactInfoDescription')">
            <div class="grid grid-cols-2 gap-6">
                <CTextField v-model="patientDetailsStore.entry.phone" :label="$t('patients.phone')" type="tel" :errors="patientDetailsStore.errors" name="phone"></CTextField>
                <CTextField v-model="patientDetailsStore.entry.mobile" :label="$t('patients.mobile')" type="tel" :errors="patientDetailsStore.errors" name="mobile"></CTextField>
            </div>
        </CAccordion>

        <CAccordion :expanded-by-default="false" :title="$t('patients.clinicalHistory')" :description="$t('patients.clinicalHistoryDescription')">
            <CDataTable v-if="patientDetailsStore.entry.symptoms.length" :disable-pagination="true" :store="patientDetailsStore" data-key="entry.symptoms" :columns="symptomsColumns"></CDataTable>
            <div v-else class="text-center text-sm text-gray-500">لا يوجد سجلات مدخلة بعد...</div>
        </CAccordion>

        <CAccordion :expanded-by-default="false" :title="$t('patients.diagnosis')" :description="$t('patients.diagnosisDescription')">
            <CDataTable v-if="patientDetailsStore.entry.diagnosis.length" :disable-pagination="true" :store="patientDetailsStore" data-key="entry.diagnosis" :columns="diagnosisColumns"></CDataTable>
            <div v-else class="text-center text-sm text-gray-500">لا يوجد سجلات مدخلة بعد...</div>
        </CAccordion>
    </c-container>
</template>

<script setup>
import { usePatientDetailsStore } from "@/modules/patients/detailStore.ts"
import { useI18n } from "vue-i18n"
import DateTime from "@/components/Table/components/DateTime.vue"

const patientDetailsStore = usePatientDetailsStore()
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

const symptomsColumns = [
    { field: "symptoms", headerName: t("patients.symptom"), sortable: false },
    { field: "record_date", headerName: t("patients.record_date"), cellRenderer: DateTime, sortable: false },
]

const diagnosisColumns = [
    { field: "diagnosis", headerName: t("patients.diagnose"), sortable: false },
    { field: "record_date", headerName: t("patients.record_date"), cellRenderer: DateTime, sortable: false },
]
</script>
