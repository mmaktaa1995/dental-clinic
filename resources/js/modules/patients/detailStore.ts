import { defineDetailPageStore, DetailPageStateTree } from "@/store/factories/detailPageStore"
import { getI18n } from "@/logic/i18n"
import axios from "axios"
import { useAccountStore } from "@/modules/auth/accountStore"

type LoadDataOptions = {
    silently?: boolean
}

type UserEntry = {
    id: number
    displayName: string
}

export type PatientEntry = {
    id: number
    name: string
    age: number
    file_number: number
    phone: string
    mobile: string
    // created_at?: string
    // updated_at?: string
    // createdBy?: UserEntry
    // lastUpdatedBy?: UserEntry
}

type PatientDetailStoreState = DetailPageStateTree & {
    entry: PatientEntry
}

export const usePatientDetailStore = defineDetailPageStore("patient-details", {
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
                    title: "عام",
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/general`
                    },
                },
                payments: {
                    title: "المدفوعات",
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/payments`
                    },
                },
            },
            watchers: {},
        }
    },
    actions: {
        async loadData(options: LoadDataOptions = {}) {
            console.log(this.isNewEntry)
            if (this.isNewEntry) {
                const accountStore = useAccountStore()
                this.entry = {
                    id: -1,
                    name: "",
                    age: 0,
                    file_number: accountStore.lastFileNumber,
                    phone: "",
                    mobile: "",
                }
                return
            }

            if (!options.silently) {
                this.isLoading = true
            }

            await axios.get(`/api/patients/${this.entryId}`).then(({ data }: { data: PatientEntry }) => {
                this.entry = data
            })
            this.errors = {}

            if (!options.silently) {
                this.isLoading = false
            }
        },
    },
})
