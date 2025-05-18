import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"

export type FileEntry = {
    id: number
    file: string
    file_name: string
    type: string
}
export const usePatientFilesStore = defineEntryListStore("patient-files-store", {
    state: () => {
        return {
            entries: null as null | FileEntry[],
            isLoading: true,
            pagination: {
                page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            order: {
                by: "patient_files.created_at",
                desc: true,
            },
            query: "",
            from_date: "",
            to_date: "",
            isDeletingFileModalOpened: false,
            fileToDelete: null,
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
    import.meta.hot.accept(acceptHMRUpdate(usePatientFilesStore, import.meta.hot))
}
