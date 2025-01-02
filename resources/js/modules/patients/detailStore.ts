import { defineDetailPageStore, DetailPageStateTree } from "@/store/factories/detailPageStore"
import { getI18n } from "@/logic/i18n"
import { api } from "@/logic/api"
import { useSettingsStore } from "@/modules/global/settingsStore"

type LoadDataOptions = {
    silently?: boolean
}

export type PatientEntry = {
    id: number
    name: string | null
    age: number | null
    gender: number | null
    file_number: number
    phone: string | null
    mobile: string | null
    files: { id: number; file: string; type: string }[]
    symptoms: { id: number; symptoms: string; record_date: string }[]
    diagnosis: { id: number; diagnosis: string; record_date: string }[]
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
    }
    filesToSave: any[]
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
            },
            filesToSave: [],
        }
    },
    actions: {
        async loadData(options: LoadDataOptions = {}) {
            if (this.isNewEntry) {
                const settingsStore = useSettingsStore()
                this.entry = {
                    id: -1,
                    name: null,
                    age: null,
                    gender: null,
                    file_number: settingsStore.lastFileNumber,
                    phone: null,
                    mobile: null,
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
