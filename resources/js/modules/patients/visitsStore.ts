import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"
import { PatientEntry } from "@/modules/patients/detailStore"

export type VisitEntry = {
    id: number
    date: string
    notes: string
    patient: PatientEntry
    created_at: string
}
export const usePatientVisitsStore = defineEntryListStore("patient-visits-store", {
    state: () => {
        return {
            entries: null as null | VisitEntry[],
            isLoading: true,
            pagination: {
                page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            order: {
                by: "date",
                desc: true,
            },
            query: "",
            from_date: "",
            to_date: "",
            isDeletingVisitModalOpened: false,
            visitToDelete: null,
            dataLoadedCallbacks: [],
        }
    },
    getters: {
        configWatcher(): any[] {
            return [this.pagination.page, this.pagination.per_page, this.query, this.from_date, this.to_date, this.order.by, this.order.desc]
        },
        entryListRequestParams(): Record<string, any> {
            return {
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                query: this.query,
                order: this.order,
                from_date: this.from_date,
                to_date: this.to_date,
            }
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(usePatientVisitsStore, import.meta.hot))
}
