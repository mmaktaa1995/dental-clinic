<template>
    <c-container>
        <CAccordion :title="$t('patients.patientInfo')" :description="$t('patients.patientInfoDescription')">
            <div class="grid grid-cols-2 gap-6">
                <CTextField v-model="patientDetailsStore.entry.file_number" :disabled="true" :label="$t('patients.fileNumber')" :errors="patientDetailsStore.errors" name="file_number"></CTextField>
                <CTextField v-model="patientDetailsStore.entry.name" :label="$t('patients.name')" :errors="patientDetailsStore.errors" name="name"></CTextField>
                <CTextField v-model="patientDetailsStore.entry.age" :label="$t('patients.age')" type="number" :errors="patientDetailsStore.errors" name="age"></CTextField>
                <CSelect v-model="patientDetailsStore.entry.gender" :options="genders" :label="$t('patients.gender')" :hint="$t('patients.selectGender')" :errors="patientDetailsStore.errors" name="gender"></CSelect>
            </div>
        </CAccordion>

        <CAccordion :title="$t('patients.patientContactInfo')" :description="$t('patients.patientContactInfoDescription')">
            <div class="grid grid-cols-2 gap-6">
                <CTextField v-model="patientDetailsStore.entry.mobile" :label="$t('patients.mobile')" type="tel" :errors="patientDetailsStore.errors" name="mobile"></CTextField>
                <CTextField v-model="patientDetailsStore.entry.phone" :label="$t('patients.phone')" type="tel" :errors="patientDetailsStore.errors" name="phone"></CTextField>
            </div>
        </CAccordion>

        <CAccordion v-if="patientDetailsStore.isNewEntry" :title="$t('patients.paymentInfo')" :description="$t('patients.paymentInfoDescription')">
            <div class="grid grid-cols-1 gap-6">
                <CTextField v-model="patientDetailsStore.entry.amount" type="number" :label="$t('payments.amount')" :errors="patientDetailsStore.errors" name="amount"></CTextField>
                <CDatePicker v-model="patientDetailsStore.entry.date" :label="$t('payments.paymentDate')" :errors="patientDetailsStore.errors" name="date"></CDatePicker>
                <CTextArea v-model="patientDetailsStore.entry.notes" :label="$t('payments.action')" :errors="patientDetailsStore.errors" name="notes"></CTextArea>
            </div>
        </CAccordion>

        <CAccordion :expanded-by-default="false">
            <template #title="{ isExpanded }">
                <div class="flex items-center justify-between w-full">
                    <div>
                        <div class="text-base font-semibold text-gray-700">
                            {{ $t("patients.clinicalHistory") }}
                        </div>
                        <p class="s-accordion__description text-sm text-gray-500">
                            {{ $t("patients.clinicalHistoryDescription") }}
                        </p>
                    </div>
                    <CButton v-if="isExpanded" type="primary" class="flex items-center" sm @click="openAddSymptomsForm">
                        <c-icon>fas fa-plus ml-1 ltr:mr-1 ltr:ml-0</c-icon>
                        <span>{{ $t("patients.addRecord") }} </span>
                    </CButton>
                </div>
            </template>
            <div v-if="isAddSymptomsOpened" class="grid gap-3 mb-4">
                <CDateTimePicker v-model="patientDetailsStore.symptom.record_date" :label="$t('patients.record_date')" :errors="patientDetailsStore.errors" name="record_date"></CDateTimePicker>
                <CTextArea v-model="patientDetailsStore.symptom.symptom" :label="$t('patients.symptom')" :errors="patientDetailsStore.errors" name="symptom"></CTextArea>
                <CButton class="flex-shrink-0" type="dark" @click="addSymptom">{{ $t("global.actions.add") }}</CButton>
            </div>
            <CDataTable v-if="!patientDetailsStore.isLoading" :store="patientSymptomsStore" :columns="symptomsColumns"></CDataTable>
        </CAccordion>

        <CAccordion :expanded-by-default="false">
            <template #title="{ isExpanded }">
                <div class="flex items-center justify-between w-full">
                    <div>
                        <div class="text-base font-semibold text-gray-700">
                            {{ $t("patients.diagnosis") }}
                        </div>
                        <p class="s-accordion__description text-sm text-gray-500">
                            {{ $t("patients.diagnosisDescription") }}
                        </p>
                    </div>
                    <CButton v-if="isExpanded" type="primary" class="flex items-center" sm @click="openAddDiagnosisForm">
                        <c-icon>fas fa-plus ml-1 ltr:mr-1 ltr:ml-0</c-icon>
                        <span>{{ $t("patients.addRecord") }} </span>
                    </CButton>
                </div>
            </template>
            <div v-if="isAddDiagnosisOpened" class="grid gap-3 mb-4">
                <CDateTimePicker v-model="patientDetailsStore.diagnose.record_date" :label="$t('patients.record_date')" :errors="patientDetailsStore.errors" name="record_date"></CDateTimePicker>
                <CTextArea v-model="patientDetailsStore.diagnose.diagnose" :label="$t('patients.diagnose')" :errors="patientDetailsStore.errors" name="diagnose"></CTextArea>
                <CButton class="flex-shrink-0" type="dark" @click="addDiagnose">{{ $t("global.actions.add") }}</CButton>
            </div>
            <CDataTable v-if="!patientDetailsStore.isLoading" :store="patientDiagnosisStore" :columns="diagnosisColumns"></CDataTable>
        </CAccordion>
    </c-container>
</template>

<script setup lang="ts">
import { usePatientDetailsStore } from "@/modules/patients/detailStore.ts"
import { useI18n } from "vue-i18n"
import DateTime from "@/components/Table/components/DateTime.vue"
import { ref } from "vue"
import { usePatientDiagnosisStore } from "@/modules/patients/patientDiagnosisStore"
import { usePatientSymptomsStore } from "@/modules/patients/patientSymptomsStore"
import { useEntryListUpdater } from "@/composables/entryListUpdater"

const isAddSymptomsOpened = ref(false)
const isAddDiagnosisOpened = ref(false)
const patientDetailsStore = usePatientDetailsStore()
const patientDiagnosisStore = usePatientDiagnosisStore()
const patientSymptomsStore = usePatientSymptomsStore()
const { t } = useI18n()

if (!patientDetailsStore.isNewEntry) {
    useEntryListUpdater(`/patients/${patientDetailsStore.entryId}/records?type=diagnosis`, patientDiagnosisStore)
    useEntryListUpdater(`/patients/${patientDetailsStore.entryId}/records?type=symptoms`, patientSymptomsStore)
}

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
    {
        field: "symptoms",
        headerName: t("patients.symptom"),
        sortable: false,
        cellClass: "bg-rose-50 animate-blink",
        cellClassCondition: (rowData: any) => rowData.id < 0,
    },
    {
        field: "record_date",
        headerName: t("patients.record_date"),
        cellRenderer: DateTime,
        cellClass: "bg-rose-50 animate-blink",
        cellClassCondition: (rowData: any) => rowData.id < 0,
    },
]

const diagnosisColumns = [
    {
        field: "diagnosis",
        headerName: t("patients.diagnose"),
        sortable: false,
        cellClass: "bg-rose-50 animate-blink",
        cellClassCondition: (rowData: any) => rowData.id < 0,
    },
    {
        field: "record_date",
        headerName: t("patients.record_date"),
        cellRenderer: DateTime,
        cellClass: "bg-rose-50 animate-blink",
        cellClassCondition: (rowData: any) => rowData.id < 0,
    },
]

const openAddSymptomsForm = (event) => {
    event.stopPropagation()
    isAddSymptomsOpened.value = !isAddSymptomsOpened.value
}

const openAddDiagnosisForm = (event) => {
    event.stopPropagation()
    isAddDiagnosisOpened.value = !isAddDiagnosisOpened.value
}

const addSymptom = () => {
    const newSymptom = {
        id: -1 * new Date().valueOf(),
        symptoms: patientDetailsStore.symptom.symptom,
        record_date: patientDetailsStore.symptom.record_date,
    }
    patientSymptomsStore.entries?.push(newSymptom)
    patientDetailsStore.entry?.symptoms.push(newSymptom)
    patientDetailsStore.symptom = {
        symptom: "",
        record_date: "",
    }
    isAddSymptomsOpened.value = false
}

const addDiagnose = () => {
    const newDiagnose = {
        id: -1 * new Date().valueOf(),
        diagnosis: patientDetailsStore.diagnose.diagnose,
        record_date: patientDetailsStore.diagnose.record_date,
    }
    patientDiagnosisStore.entries?.push(newDiagnose)
    patientDetailsStore.entry?.diagnosis.push(newDiagnose)
    patientDetailsStore.diagnose = {
        diagnose: "",
        record_date: "",
    }
    isAddDiagnosisOpened.value = false
}
</script>
