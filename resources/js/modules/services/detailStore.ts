import { defineDetailPageStore, DetailPageStateTree } from "@/store/factories/detailPageStore"
import { getI18n } from "@/logic/i18n"
import { api } from "@/logic/api"

type LoadDataOptions = {
    silently?: boolean
}

export type ServiceEntry = {
    id: number
    name: string
    price: number | null
}

type ServiceDetailStoreState = DetailPageStateTree & {
    entry: ServiceEntry
}

export const useServiceDetailsStore = defineDetailPageStore("service-details", {
    state: (): ServiceDetailStoreState => {
        // @ts-ignore
        const { t } = getI18n()

        return {
            entryId: 0,
            entry: {} as ServiceEntry,
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
                    price: null,
                }
                return
            }

            if (!options.silently) {
                this.isLoading = true
            }

            await api.get(`/services/${this.entryId}`).then((response: ServiceEntry) => {
                this.entry = response
            })
            this.errors = {}

            if (!options.silently) {
                this.isLoading = false
            }
        },
    },
})
