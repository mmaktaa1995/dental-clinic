import { defineDetailPageStore, DetailPageStateTree } from "@/store/factories/detailPageStore"
import { getI18n } from "@/logic/i18n"
import axios from "axios"
import { useAccountStore } from "@/modules/auth/accountStore"
import { api } from "@/logic/api"

type LoadDataOptions = {
    silently?: boolean
}

export type PatientEntry = {
    id: number
    name: string
    age: number | null
    gender: number | null
    file_number: number
    phone: string
    mobile: string
    files: { id: number; file: string; type: string }[]
    // created_at?: string
    // updated_at?: string
    // createdBy?: UserEntry
    // lastUpdatedBy?: UserEntry
}

type PatientDetailStoreState = DetailPageStateTree & {
    entry: PatientEntry
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
                },
                payments: {
                    title: t("payments.title"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/payments`
                    },
                },
                debits: {
                    title: t("payments.debits"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/debits`
                    },
                },
            },
            watchers: {},
            filesToSave: [],
        }
    },
    actions: {
        async loadData(options: LoadDataOptions = {}) {
            if (this.isNewEntry) {
                const accountStore = useAccountStore()
                this.entry = {
                    id: -1,
                    name: "",
                    age: null,
                    gender: null,
                    file_number: accountStore.lastFileNumber,
                    phone: "",
                    mobile: "",
                    files: [],
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
