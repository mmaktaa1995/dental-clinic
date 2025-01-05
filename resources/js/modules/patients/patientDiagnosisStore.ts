import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"

type PatientDiagnoseEntry = {
    id: number
    diagnosis: string
    record_date: string
    teeth: []
    teethIds: Record<any, number>
}

export const usePatientDiagnosisStore = defineEntryListStore("patient-diagnosis-store", {
    state: () => {
        return {
            entries: null as null | PatientDiagnoseEntry[],
            isLoading: true,
            pagination: {
                page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            order: {
                by: "record_date",
                desc: true,
            },
            query: "",
            dataLoadedCallbacks: [],
        }
    },
    getters: {
        configWatcher(): any[] {
            return [this.pagination.page, this.pagination.per_page, this.query, this.order.by, this.order.desc]
        },
        entryListRequestParams(): Record<string, any> {
            return {
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                query: this.query,
                order: this.order,
            }
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(usePatientDiagnosisStore, import.meta.hot))
}
