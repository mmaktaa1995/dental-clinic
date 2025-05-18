import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"
import { PatientEntry } from "@/modules/patients/detailStore"

export const usePatientsStore = defineEntryListStore("patients-store", {
    state: () => {
        return {
            entries: null as null | PatientEntry[],
            isLoading: true,
            pagination: {
                page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            order: {
                by: "created_at",
                desc: true,
            },
            query: "",
            from_date: "",
            file_number: null,
            to_date: "",
            dataLoadedCallbacks: [],
        }
    },
    getters: {
        configWatcher(): any[] {
            return [this.pagination.page, this.pagination.per_page, this.query, this.from_date, this.to_date, this.file_number, this.order.by, this.order.desc]
        },
        entryListRequestParams(): Record<string, any> {
            return {
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                query: this.query,
                order: this.order,
                from_date: this.from_date,
                to_date: this.to_date,
                file_number: this.file_number,
            }
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(usePatientsStore, import.meta.hot))
}
