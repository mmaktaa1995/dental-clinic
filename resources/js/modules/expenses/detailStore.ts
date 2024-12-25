import { defineDetailPageStore, DetailPageStateTree } from "@/store/factories/detailPageStore"
import { getI18n } from "@/logic/i18n"
import { api } from "@/logic/api"

type LoadDataOptions = {
    silently?: boolean
}

export type ExpenseEntry = {
    id: number
    name: string
    date: string
    description: string
    amount: number | null
    created_at: string
}

type ExpenseDetailStoreState = DetailPageStateTree & {
    entry: ExpenseEntry
}

export const useExpenseDetailsStore = defineDetailPageStore("expense-details", {
    state: (): ExpenseDetailStoreState => {
        // @ts-ignore
        const { t } = getI18n()

        return {
            entryId: 0,
            entry: {} as ExpenseEntry,
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
            },
            watchers: {},
        }
    },
    actions: {
        async loadData(options: LoadDataOptions = {}) {
            if (this.isNewEntry) {
                this.entry = {
                    id: -1,
                    name: "",
                    amount: null,
                    description: "",
                    date: "",
                    created_at: "",
                }
                return
            }

            if (!options.silently) {
                this.isLoading = true
            }

            await api.get(`/expenses/${this.entryId}`).then((response: ExpenseEntry) => {
                this.entry = response
            })
            this.errors = {}

            if (!options.silently) {
                this.isLoading = false
            }
        },
    },
})
