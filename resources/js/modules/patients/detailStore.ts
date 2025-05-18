import { defineDetailPageStore, DetailPageStateTree } from "@/store/factories/detailPageStore"
import { getI18n } from "@/logic/i18n"
import { api } from "@/logic/api"
import { useSettingsStore } from "@/modules/global/settingsStore"

type LoadDataOptions = {
    silently?: boolean
}

type PatientRecordToothEntry = {
    id: number
    patient_record_id: number
    tooth_id: number
    is_treated: number
    description: null
}

export type PatientEntry = {
    id: number
    name: string | null
    age: number | null
    gender: number | null
    file_number: number
    phone: string | null
    amount?: number | null
    date?: string | null
    notes?: string | null
    mobile: string | null
    files: { id: number; file: string; type: string }[]
    symptoms: { id: number; symptoms: string; record_date: string }[]
    diagnosis: { id: number; diagnosis: string; record_date: string }[]
    affected_teeth: PatientRecordToothEntry[]
    // created_at?: string
    // updated_at?: string
    // createdBy?: UserEntry
    // lastUpdatedBy?: UserEntry
}

type PatientDetailStoreState = DetailPageStateTree & {
    entry: PatientEntry
    symptom: {
        id?: number
        symptom: string
        record_date: string
    }
    diagnose: {
        id?: number
        diagnose: string
        record_date: string
        teeth: Record<any, any>
    }
    filesToSave: any[]
    genericError: Record<any, any>
}

export const usePatientDetailsStore = defineDetailPageStore("patient-details", {
    state: (): PatientDetailStoreState => {
        // @ts-ignore
        const { t } = getI18n()

        return {
            entryId: 0,
            entry: {} as PatientEntry,
            errors: {},
            isLoading: false as boolean,
            subPages: {
                general: {
                    title: t("global.general"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/general`
                    },
                },
                files: {
                    title: t("patients.files"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/files`
                    },
                    visible: (store) => !store.isNewEntry,
                },
                payments: {
                    title: t("payments.title"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/payments`
                    },
                    visible: (store) => !store.isNewEntry,
                },
                debits: {
                    title: t("payments.debits"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/debits`
                    },
                    visible: (store) => !store.isNewEntry,
                },
                visits: {
                    title: t("patients.visits"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/visits`
                    },
                    visible: (store) => !store.isNewEntry,
                },
            },
            watchers: {},
            symptom: {
                symptom: "",
                record_date: "",
            },
            diagnose: {
                diagnose: "",
                record_date: "",
                teeth: {},
            },
            filesToSave: [],
            genericError: {},
        }
    },
    actions: {
        async loadData(options: LoadDataOptions = {}) {
            if (this.isNewEntry) {
                const settingsStore = useSettingsStore()
                this.entry = {
                    id: -1,
                    affected_teeth: [],
                    name: null,
                    age: null,
                    gender: null,
                    file_number: settingsStore.lastFileNumber,
                    phone: null,
                    mobile: null,
                    amount: null,
                    date: null,
                    notes: null,
                    files: [],
                    symptoms: [],
                    diagnosis: [],
                }
                return
            }

            if (!options.silently) {
                this.isLoading = true
            }

            await api.get(`/patients/${this.entryId}`).then((response: PatientEntry) => {
                this.entry = response
            })
            this.errors = {}

            if (!options.silently) {
                this.isLoading = false
            }
        },
    },
})
