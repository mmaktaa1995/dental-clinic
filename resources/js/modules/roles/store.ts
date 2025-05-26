import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"

export interface RoleEntry {
    id: number
    name: string
    slug: string
    permissions: Array<{
        id: number
        name: string
        slug: string
    }>
    created_at: string
}

export const useRolesStore = defineEntryListStore("roles-store", {
    state: () => {
        return {
            entries: null as null | RoleEntry[],
            isLoading: true,
            pagination: {
                page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            order: {
                by: "name",
                desc: false,
            },
            query: "",
            permission: "",
            dataLoadedCallbacks: [],
        }
    },
    getters: {
        configWatcher(): any[] {
            return [this.pagination.page, this.pagination.per_page, this.query, this.permission, this.order.by, this.order.desc]
        },
        entryListRequestParams(): Record<string, any> {
            return {
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                query: this.query,
                permission: this.permission,
                order: this.order,
            }
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(useRolesStore, import.meta.hot))
}
