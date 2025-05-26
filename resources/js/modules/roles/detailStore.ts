import { acceptHMRUpdate } from "pinia"
import { defineDetailPageStore } from "@/store/factories/detailPageStore"
import { api } from "@/logic/api"
import { getI18n } from "@/logic/i18n"
import { DetailPageStateTree } from "@/store/factories/detailPageStore"

export interface RoleDetailEntry {
    id?: number
    name: string
    slug: string
    permissions: number[]
}

type RoleDetailStoreState = DetailPageStateTree & {
    entry: RoleDetailEntry
    errors: Record<any, any>
    isLoading: boolean
    isSaving: boolean
    entryId: number | null
    isNewEntry: boolean
    genericError: Record<any, any>
    watchers: Record<any, any>
    availablePermissions: Array<{ id: number; name: string; slug: string }>
}

export const useRoleDetailsStore = defineDetailPageStore("role-details-store", {
    state: (): RoleDetailStoreState => {
        // @ts-ignore
        const { t } = getI18n()

        return {
            entry: {
                name: "",
                slug: "",
                permissions: [],
            },
            errors: {},
            isLoading: false,
            isSaving: false,
            entryId: -1,
            genericError: {},
            watchers: {},
            availablePermissions: [] as Array<{ id: number; name: string; slug: string }>,
            subPages: {
                general: {
                    title: t("global.general"),
                    isDirty: false,
                    buildSubPath() {
                        return `/${this.entryId}/general`
                    },
                },
            },
        }
    },
    actions: {
        async loadData(options: { silently?: boolean } = {}) {
            if (this.isNewEntry) {
                this.entry = {
                    id: -1,
                    name: "",
                    slug: "",
                    permissions: [],
                }
                return
            }

            if (!options.silently) {
                this.isLoading = true
            }

            await api.get(`/roles/${this.entryId}`).then((response: RoleDetailEntry) => {
                this.entry = response
                this.entry.permissions = response.permissions.map((permission: any) => permission.id)
            })
            this.errors = {}

            if (!options.silently) {
                this.isLoading = false
            }
        },
        resetEntry() {
            this.entry = {
                name: "",
                slug: "",
                permissions: [],
            }
            this.errors = {}
            this.genericError = {}
        },
        async loadPermissions() {
            try {
                const data = await api.get("/permissions")
                this.availablePermissions = data || []
                console.log(this.availablePermissions, data)
            } catch (error) {
                console.error("Failed to load permissions", error)
            }
        }
    },
    getters: {
        apiEndpoint(): string {
            return "/roles"
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(useRoleDetailsStore, import.meta.hot))
}
