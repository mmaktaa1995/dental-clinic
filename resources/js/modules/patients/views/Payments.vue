<template>
    <c-container>
        <div class="w-full text-left">
            <CButton sm type="primary" class="ml-2 ltr:ml-0 ltr:mr-2" @click="openAddPayment"> {{ $t("payments.addPayment") }} </CButton>
            <CButton sm type="accent" @click="patientPaymentsStore.print(patientDetailStore.entryId)"> {{ $t("global.actions.print") }} </CButton>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
            <div class="">
                <label class="block text-sm font-medium text-gray-700 text-right">{{ $t("payments.totalAmount") }}</label>
                <label class="block text-2xl font-medium text-teal-600 text-right">{{ formattedValue(patientPaymentsStore.totalPayments) }}</label>
            </div>
            <div class="">
                <label class="block text-sm font-medium text-gray-700 text-right">{{ $t("payments.totalRemainingAmount") }}</label>
                <label class="block text-2xl font-medium text-pink-600 text-right">{{ formattedValue(patientPaymentsStore.totalRemainingPayments) }}</label>
            </div>
        </div>
        <hr class="my-4" />
        <div class="sm:block mt-4">
            <nav class="flex gap-2">
                <a
                    href="#"
                    :class="'px-3 py-2 font-medium text-sm leading-5 rounded-md transition text-gray-500 hover:bg-pink-200 hover:text-pink-600 focus:outline-none focus:text-pink-600 focus:bg-pink-200 ' + (currentTab === 'PAYMENTS' ? 'text-pink-700 bg-pink-200' : '')"
                    @click.prevent="(currentTab = 'PAYMENTS')"
                >
                    {{ $t("payments.title") }}
                </a>
                <a
                    href="#"
                    :class="'ml-4 px-3 py-2 font-medium text-sm leading-5 rounded-md transition text-gray-500 hover:bg-pink-200 hover:text-pink-600 focus:outline-none focus:text-pink-600 focus:bg-pink-200 ' + (currentTab === 'DELETED_PAYMENTS' ? 'text-pink-700 bg-pink-200' : '')"
                    @click.prevent="(currentTab = 'DELETED_PAYMENTS')"
                >
                    {{ $t("payments.deletedPayments") }}
                </a>
            </nav>
        </div>

        <div>
            <PaymentsTable :store="patientPaymentsStore" />
        </div>
        <teleport to=".modal-teleport">
            <AddPayment v-model="paymentDetailsStore.isAddPaymentModalOpened" :reload="reload" :patient="paymentDetailsStore.patient" :payment="paymentDetailsStore.payment"></AddPayment>
        </teleport>
    </c-container>
</template>

<script setup lang="ts">
import { usePatientDetailsStore } from "@/modules/patients/detailStore"
import { usePatientPaymentsStore } from "@/modules/patients/paymentsStore"
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { ref, watch } from "vue"
import PaymentsTable from "@/modules/payments/components/PaymentsTable.vue"
import { formatNumber } from "@/logic/helpers"
import { useI18n } from "vue-i18n"
import AddPayment from "@/modules/payments/components/AddPayment.vue"
import { usePaymentDetailsStore } from "@/modules/payments/detailStore"

const patientDetailStore = usePatientDetailsStore()
const paymentDetailsStore = usePaymentDetailsStore()
const patientPaymentsStore = usePatientPaymentsStore()
const { t } = useI18n()

const currentTab = ref("PAYMENTS")
watch(
    () => currentTab.value,
    () => {
        if (currentTab.value === "PAYMENTS") {
            patientPaymentsStore.deleted = 0
        } else {
            patientPaymentsStore.deleted = 1
        }
    },
)

const formattedValue = (value: number) => {
    return formatNumber(value)
}

const { reload } = useEntryListUpdater(`/payments/${patientDetailStore.entryId}/patients`, patientPaymentsStore, async (response) => {
    patientPaymentsStore.totalPayments = response.total_payments
    patientPaymentsStore.totalRemainingPayments = response.total_remaining_payments
    patientDetailStore.subPages.payments.title = t("payments.title") + ` (${patientPaymentsStore.pagination.total})`
})

const openAddPayment = () => {
    paymentDetailsStore.patient = patientDetailStore.entry
    paymentDetailsStore.isAddPaymentModalOpened = true
}
</script>
