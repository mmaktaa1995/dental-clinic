import { defineDetailPageStore, DetailPageStateTree } from "@/store/factories/detailPageStore"
import { PaymentEntry } from "@/modules/payments/store"
import { PatientEntry } from "@/modules/patients/detailStore"

type PaymentDetailStoreState = DetailPageStateTree & {
    entry: PaymentEntry
    addPaymentModalTitle: string
    patient?: PatientEntry
    payment?: PaymentEntry
    isAddPaymentModalOpened: boolean
    isDeletePaymentModalOpened: boolean
    isEdit: boolean
}

export const usePaymentDetailsStore = defineDetailPageStore("payment-details", {
    state: (): PaymentDetailStoreState => {
        return {
            entryId: 0,
            entry: {} as PaymentEntry,
            errors: {},
            addPaymentModalTitle: "payments.addPayment",
            patient: undefined as unknown as PatientEntry,
            payment: undefined,
            isLoading: false as boolean,
            isAddPaymentModalOpened: false as boolean,
            isDeletePaymentModalOpened: false as boolean,
            isEdit: false as boolean,
            subPages: {},
        }
    },
    actions: {},
})
