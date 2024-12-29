import { defineDetailPageStore } from "@/store/factories/detailPageStore"
import { PaymentEntry } from "@/modules/payments/store"

export const usePaymentDetailsStore = defineDetailPageStore("payment-details", {
    state: () => {
        return {
            entryId: 0,
            entry: {} as PaymentEntry,
            errors: {},
            patient: undefined,
            payment: undefined,
            isLoading: false as boolean,
            isAddPaymentModalOpened: false as boolean,
            isEdit: false as boolean,
        }
    },
    actions: {},
})
