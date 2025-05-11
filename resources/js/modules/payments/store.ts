import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"
import { api } from "@/logic/api"
import { PatientEntry } from "@/modules/patients/detailStore"

export type PaymentEntry = {
    id: number
    notes: string | null
    amount: number | null
    remaining_amount: number | null
    patient_id: number | null
    patient: PatientEntry
    visit: { notes: string }
    date: string | null
    isEdit: boolean
    isPayDebtOpened: boolean
}
export const usePaymentsStore = defineEntryListStore("payments-store", {
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
                by: "created_at",
                desc: true,
            },
            query: "",
            from_date: "",
            to_date: "",
            deleted: 0,
            totalPayments: 0,
            totalRemainingPayments: 0,
            patient: undefined,
            payment: undefined,
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
    import.meta.hot.accept(acceptHMRUpdate(usePaymentsStore, import.meta.hot))
}
