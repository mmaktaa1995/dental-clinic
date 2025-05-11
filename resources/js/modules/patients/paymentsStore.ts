import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"
import { api } from "@/logic/api"

export type PaymentEntry = {
    notes: string
    amount: number
    remaining_amount: number
    date: string
    isEdit: boolean
    isPayDebtOpened: boolean
}
export const usePatientPaymentsStore = defineEntryListStore("patient-payments-store", {
    state: () => {
        return {
            entries: null as null | PaymentEntry[],
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
            deleted: 0,
            totalPayments: 0,
            totalRemainingPayments: 0,
            dataLoadedCallbacks: [],
        }
    },
    getters: {
        configWatcher(): any[] {
            return [this.pagination.page, this.pagination.per_page, this.query, this.from_date, this.to_date, this.order.by, this.order.desc, this.deleted]
        },
        entryListRequestParams(): Record<string, any> {
            return {
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                query: this.query,
                order: this.order,
                from_date: this.from_date,
                to_date: this.to_date,
                deleted: this.deleted,
            }
        },
    },

    actions: {
        print(patientId: number) {
            api.get(`/payments/${patientId}/patients/print`).then((response) => {
                window.open(response.url, "blank")
            })
        },
    },
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(usePatientPaymentsStore, import.meta.hot))
}
