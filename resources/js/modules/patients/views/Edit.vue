<template>
    <div v-if="patientDetailsStore.genericError.message && patientDetailsStore.genericError.code === 'EXIST'" class="px-4 py-3 sm:p-6 bg-rose-100 bg-opacity-50 text-rose-800 border-b border-rose-800">
        <p>
            {{ patientDetailsStore.genericError.message }}.
            <a :href="`/patients/${patientDetailsStore.genericError.id}/general`" class="hover:underline font-medium">{{ $t("global.checkHere") }}</a>
        </p>
    </div>
    <c-container>
        <CAccordion :title="$t('patients.patientInfo')" :description="$t('patients.patientInfoDescription')">
            <div class="grid grid-cols-2 gap-6">
                <CTextField v-model="patientDetailsStore.entry.file_number" :disabled="true" :label="$t('patients.fileNumber')" :errors="patientDetailsStore.errors" name="file_number"></CTextField>
                <CTextField v-model="patientDetailsStore.entry.name" :label="$t('patients.name')" :errors="patientDetailsStore.errors" name="name" @input="checkPatientExistance"></CTextField>
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
                <CButton class="flex-shrink-0" type="dark" @click="saveSymptom">{{ patientDetailsStore.symptom.id && patientDetailsStore.symptom.id > 0 ? $t("patients.editRecord") : $t("patients.addRecord") }}</CButton>
            </div>
            <CDataTable v-if="!patientDetailsStore.isLoading" :store="patientSymptomsStore" :columns="symptomsColumns" @row-clicked="editSymptom"></CDataTable>
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
                <p v-if="getSelectedDiagnoseTeeth">
                    {{ $t("patients.affectedTeethNumbers") }}:
                    <span dir="ltr">{{ getSelectedDiagnoseTeeth }}</span>
                </p>
                <CButton sm class="max-w-60" type="info" @click="(isSelectTeethOpened = true)">{{ $t("patients.linkAffectedTeeth") }}</CButton>
                <CButton class="flex-shrink-0" type="dark" @click="saveDiagnose">{{ patientDetailsStore.diagnose?.id && patientDetailsStore.diagnose?.id > 0 ? $t("patients.editRecord") : $t("patients.addRecord") }}</CButton>
            </div>
            <CDataTable v-if="!patientDetailsStore.isLoading" :store="patientDiagnosisStore" :columns="diagnosisColumns" @row-clicked="editDiagnose"></CDataTable>
        </CAccordion>
    </c-container>
    <teleport to=".modal-teleport">
        <TeethDialog v-if="isAddDiagnosisOpened" v-model="isSelectTeethOpened" v-model:treated-teeth="treatedTeeth" v-model:teeth="patientDetailsStore.diagnose.teeth" :affected-teeth="affectedTeeth" @teeth-selected="selectDiagnoseTeeth"></TeethDialog>
    </teleport>
</template>

<script setup lang="ts">
import { usePatientDetailsStore } from "@/modules/patients/detailStore"
import { useI18n } from "vue-i18n"
import DateTime from "@/components/Table/components/DateTime.vue"
import { computed, reactive, ref } from "vue"
import { usePatientDiagnosisStore } from "@/modules/patients/patientDiagnosisStore"
import { usePatientSymptomsStore } from "@/modules/patients/patientSymptomsStore"
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { api } from "@/logic/api"
import TeethDialog from "@/components/TeethDialog.vue"
import { useSettingsStore } from "@/modules/global/settingsStore"
import CellTeeth from "@/modules/patients/components/table/CellTeeth.vue"
import { useToastStore } from "@/modules/global/toastStore"

const isSelectTeethOpened = ref(false)
const isAddSymptomsOpened = ref(false)
const isAddDiagnosisOpened = ref(false)
const settingsStore = useSettingsStore()
const toastStore = useToastStore()
const patientDetailsStore = usePatientDetailsStore()
const patientDiagnosisStore = usePatientDiagnosisStore()
const patientSymptomsStore = usePatientSymptomsStore()
const { t } = useI18n()
let diagnosisReload: any
let symptomsReload: any
let treatedTeeth = reactive<Record<any, any>>({})
let affectedTeeth = reactive<Record<any, any>>({})
let tempAffectedTeeth = reactive<Record<any, any>>({})

function preparePatientAffectedTreatedTeeth() {
    affectedTeeth = {}
    tempAffectedTeeth = {}
    treatedTeeth = {}
    patientDetailsStore.entry?.affected_teeth?.forEach((tooth) => {
        if (!tooth.is_treated) {
            affectedTeeth[tooth.tooth_id] = tooth.tooth_id
            tempAffectedTeeth[tooth.tooth_id] = tooth.tooth_id
        } else {
            treatedTeeth[tooth.tooth_id] = tooth.tooth_id
        }
    })
}

if (!patientDetailsStore.isNewEntry) {
    const { reload } = useEntryListUpdater(`/patients/${patientDetailsStore.entryId}/records?type=diagnosis`, patientDiagnosisStore)
    diagnosisReload = reload
    const symptomsEntryListUpdater = useEntryListUpdater(`/patients/${patientDetailsStore.entryId}/records?type=symptoms`, patientSymptomsStore)
    symptomsReload = symptomsEntryListUpdater.reload
    preparePatientAffectedTreatedTeeth()
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
        field: "teethIds",
        headerName: t("patients.affectedTeethNumbers"),
        cellRenderer: CellTeeth,
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

const openAddSymptomsForm = (event: any) => {
    event.stopPropagation()
    isAddSymptomsOpened.value = true
    patientDetailsStore.symptom = {
        symptom: "",
        record_date: "",
    }
}

const openAddDiagnosisForm = (event: any) => {
    event.stopPropagation()
    isAddDiagnosisOpened.value = true
    patientDetailsStore.diagnose = {
        diagnose: "",
        record_date: "",
        teeth: {},
    }
    resetAffectedTeeth()
}

const addSymptom = () => {
    const newSymptom = {
        id: -1 * new Date().valueOf(),
        symptoms: patientDetailsStore.symptom.symptom,
        record_date: patientDetailsStore.symptom.record_date,
    }
    patientSymptomsStore.entries?.push(newSymptom as any)
    patientDetailsStore.entry?.symptoms.push(newSymptom)
    patientDetailsStore.symptom = {
        symptom: "",
        record_date: "",
    }
    isAddSymptomsOpened.value = false
}

const updateSymptom = async () => {
    await api.patch(`/patients/${patientDetailsStore.entryId}/records/${patientDetailsStore.symptom.id}`, {
        record_date: patientDetailsStore.symptom.record_date,
        symptoms: patientDetailsStore.symptom.symptom,
    })

    patientDetailsStore.symptom = {
        symptom: "",
        record_date: "",
    }
    isAddSymptomsOpened.value = false
    symptomsReload?.()
}

const saveSymptom = () => {
    if (patientDetailsStore.symptom.id && patientDetailsStore.symptom.id > 0) {
        updateSymptom()
    } else {
        addSymptom()
    }
}

function resetAffectedTeeth() {
    Object.values(tempAffectedTeeth).forEach((toothId) => {
        affectedTeeth[toothId] = toothId
    })
}

const saveDiagnose = () => {
    if (patientDetailsStore.diagnose.id && patientDetailsStore.diagnose.id > 0) {
        updateDiagnose()
    } else {
        addDiagnose()
    }
    resetAffectedTeeth()
}

const updateDiagnose = async () => {
    let oldTeeth: any = patientDiagnosisStore.entries?.find((diagnose) => patientDetailsStore.diagnose.id === diagnose.id)?.teethIds
    if (Array.isArray(oldTeeth)) {
        oldTeeth = {}
    }
    oldTeeth = Object.values(oldTeeth)
    const updatedTeeth = Object.values(patientDetailsStore.diagnose.teeth)
    await api.patch(`/patients/${patientDetailsStore.entryId}/records/${patientDetailsStore.diagnose.id}`, {
        record_date: patientDetailsStore.diagnose.record_date,
        diagnosis: patientDetailsStore.diagnose.diagnose,
        teeth: updatedTeeth,
    })

    patientDetailsStore.diagnose = {
        diagnose: "",
        record_date: "",
        teeth: {},
    }
    isAddDiagnosisOpened.value = false
    diagnosisReload?.()
    const deletedTeeth: number[] = (oldTeeth as []).filter((toothId) => !updatedTeeth.includes(toothId))
    const addedTeeth = updatedTeeth.filter((toothId) => !oldTeeth.includes(toothId))
    if (deletedTeeth.length) {
        patientDetailsStore.entry.affected_teeth = patientDetailsStore.entry.affected_teeth.filter((tooth) => !deletedTeeth.includes(+tooth.tooth_id))
    }

    addedTeeth.forEach((toothId) => {
        if (!patientDetailsStore.entry?.affected_teeth) {
            patientDetailsStore.entry.affected_teeth = []
        }
        patientDetailsStore.entry?.affected_teeth.push({ is_treated: 0, tooth_id: toothId } as any)
    })

    if (addedTeeth.length || deletedTeeth.length) {
        patientDetailsStore.watchers?.entry?.resetStore()
    }
    preparePatientAffectedTreatedTeeth()
}

const addDiagnose = () => {
    const newDiagnose = {
        id: -1 * new Date().valueOf(),
        diagnosis: patientDetailsStore.diagnose.diagnose,
        record_date: patientDetailsStore.diagnose.record_date,
        teeth: Object.values(patientDetailsStore.diagnose.teeth),
        teeth_ids: Object.values(patientDetailsStore.diagnose.teeth),
    }
    patientDiagnosisStore.entries?.push(newDiagnose as any)
    patientDetailsStore.entry?.diagnosis.push(newDiagnose)
    patientDetailsStore.diagnose = {
        diagnose: "",
        record_date: "",
        teeth: {},
    }
    isAddDiagnosisOpened.value = false
}

const editDiagnose = (rowData: any) => {
    if (rowData.id < 0) {
        return
    }
    const row = JSON.parse(JSON.stringify(rowData))
    resetAffectedTeeth()
    Object.values(row.teethIds).forEach((toothId) => {
        const toothNumber = settingsStore.teeth.find((sTooth) => sTooth.id === toothId)?.number
        if (toothNumber && affectedTeeth[toothNumber]) {
            delete affectedTeeth[toothNumber]
        }
    })
    isAddDiagnosisOpened.value = true
    patientDetailsStore.diagnose = {
        id: row.id,
        diagnose: row.diagnosis,
        record_date: row.record_date,
        teeth: row.teethIds,
    }
}

const editSymptom = (rowData: any) => {
    if (rowData.id < 0) {
        return
    }

    isAddSymptomsOpened.value = true
    patientDetailsStore.symptom = {
        id: rowData.id,
        symptom: rowData.symptoms,
        record_date: rowData.record_date,
    }
}

const selectDiagnoseTeeth = () => {
    isSelectTeethOpened.value = false
}

const checkPatientExistance = async () => {
    patientDetailsStore.genericError = {}
    if (!patientDetailsStore.entry.name || !patientDetailsStore.isNewEntry) {
        patientDetailsStore.genericError = {}
        return
    }
    const response = await api.post("/patients/exist", { query: patientDetailsStore.entry.name })
    if (response.id && response.file_number) {
        toastStore.error(response.message)
        patientDetailsStore.genericError = {
            id: response.id,
            message: response.message,
            code: "EXIST",
        }
        patientDetailsStore.watchers?.entry?.resetStore()
    }
}

const getSelectedDiagnoseTeeth = computed(() => {
    if (patientDetailsStore.diagnose.teeth) {
        const teethNumbers = Object.values(patientDetailsStore.diagnose.teeth)
        return settingsStore.teeth
            .filter((tooth) => teethNumbers.includes(tooth.id))
            .map((tooth) => `${tooth.number} - ${tooth.name}`)
            .join(", ")
    }
    return ""
})
</script>
