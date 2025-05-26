import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"

export interface UserEntry {
    id: number
    name: string
    email: string
    created_at: string
    roles: Array<{
        id: number
        name: string
        slug: string
    }>
}

export const useUsersStore = defineEntryListStore("users-store", {
    state: () => {
        return {
            entries: null as null | UserEntry[],
            isLoading: true,
            query: "",
            email: "",
            pagination: {
                page: 1,
                last_page: 1,
                total: 0,
                per_page: 15,
            },
            order: {
                by: "created_at",
                desc: true,
            },
            dataLoadedCallbacks: [],
        }
    },
    getters: {
        configWatcher(): any[] {
            return [this.pagination.page, this.pagination.per_page, this.query, this.email, this.order.by, this.order.desc]
        },
        entryListRequestParams(): Record<string, any> {
            return {
                query: this.query,
                email: this.email,
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                order: this.order,
            }
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(useUsersStore, import.meta.hot))
}
