import { acceptHMRUpdate } from "pinia"
import { defineDetailPageStore } from "@/store/factories/detailPageStore"
import { getI18n } from "@/logic/i18n"
import { api } from "@/logic/api"

export interface UserDetailEntry {
    id?: number
    name: string
    email: string
    password?: string
    password_confirmation?: string
    roles: number[]
}

export const useUserDetailsStore = defineDetailPageStore("user-details-store", {
    state: () => {
        // @ts-ignore
        const { t } = getI18n()

        return {
            entry: {
                id: -1,
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                roles: [],
            } as UserDetailEntry,
            errors: {},
            isLoading: false as boolean,
            isSaving: false,
            entryId: -1,
            genericError: {},
            watchers: {},
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
                    email: "",
                    password: "",
                    password_confirmation: "",
                    roles: [],
                }
                return
            }

            if (!options.silently) {
                this.isLoading = true
            }

            await api.get(`/users/${this.entryId}`).then((response: UserDetailEntry) => {
                this.entry = response
                this.entry.roles = response.roles.map((role: any) => role.id)
            })
            this.errors = {}

            if (!options.silently) {
                this.isLoading = false
            }
        },
        resetEntry() {
            this.entry = {
                id: -1,
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                roles: [],
            }
            this.errors = {}
            this.genericError = {}
        },
    },
    getters: {
        apiEndpoint(): string {
            return "/users"
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(useUserDetailsStore, import.meta.hot))
}
