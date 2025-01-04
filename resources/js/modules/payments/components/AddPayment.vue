<template>
    <!-- eslint-disable vue/no-mutating-props -->

    <CDialog v-model="isAddPaymentOpen" type="warning">
        <template #header>
            {{ $t(title) }}
        </template>
        <template #body>
            <div class="min-h-[40vh]">
                <div class="grid grid-cols-1 w-full gap-4">
                    <CTextField v-if="patient" v-model="patient.name" disabled :label="$t('patients.name')" name="name"></CTextField>
                    <CAutocomplete v-else v-model="paymentDetailsStore.entry.patient_id" v-model:object="paymentDetailsStore.entry.patient" :fetch-items="fetchPatientsData" :errors="paymentDetailsStore.errors" :label="$t('patients.name')" name="patient_id"></CAutocomplete>
                    <CDatePicker v-model="paymentDetailsStore.entry.date" :label="$t('payments.date')" :errors="paymentDetailsStore.errors" name="date"></CDatePicker>
                    <CTextArea v-model="paymentDetailsStore.entry.notes" :label="$t('payments.action')" :errors="paymentDetailsStore.errors" name="notes"></CTextArea>
                    <hr class="my-4" />
                    <CTextField v-model="paymentDetailsStore.entry.amount" :label="$t('payments.amount')" type="number" :errors="paymentDetailsStore.errors" name="amount"></CTextField>
                    <CTextField v-model="paymentDetailsStore.entry.remaining_amount" :disabled="!!payment && !isEdit" :label="$t('payments.remainingAmount')" :errors="paymentDetailsStore.errors" name="remaining_amount"></CTextField>
                </div>
            </div>
        </template>
        <template #actions>
            <CButton @click="close">{{ $t("global.actions.cancel") }}</CButton>
            <AsyncButton :loading="isCreating" type="primary" @click="createPayment">
                {{ $t("global.actions.add") }}
            </AsyncButton>
        </template>
    </CDialog>
</template>

<script setup lang="ts">
import { PatientEntry } from "@/modules/patients/detailStore"
import { api } from "@/logic/api"
import { PaymentEntry } from "@/modules/payments/store"
import { computed, ref, watch } from "vue"
import AsyncButton from "@/components/AsyncButton.vue"
import { usePaymentDetailsStore } from "@/modules/payments/detailStore"
import { format } from "date-fns"

const isAddPaymentOpen = defineModel<boolean>({ required: true })

const props = withDefaults(
    defineProps<{
        isEdit?: boolean
        patient?: PatientEntry
        payment?: PaymentEntry
        reload?: () => void
    }>(),
    {
        patient: undefined,
        payment: undefined,
        reload: undefined,
    },
)

const paymentDetailsStore = usePaymentDetailsStore()

const isCreating = ref(false)
const title = computed(() => {
    if (props.isEdit) {
        return "payments.editPayment"
    }
    if (props.payment && !props.isEdit) {
        return "payments.addDebtPayment"
    }
    return "payments.addPayment"
})

const fetchPatientsData = async (page: number, searchQuery: string) => {
    return await api.get(`/patients/list?per_page=20&page=${page}&query=${searchQuery}`)
}

const close = () => {
    isAddPaymentOpen.value = false
}

const createPayment = async () => {
    if (isCreating.value) {
        return
    }
    isCreating.value = true
    try {
        const body = {
            patient_id: paymentDetailsStore.entry.patient_id,
            amount: paymentDetailsStore.entry.amount,
            remaining_amount: paymentDetailsStore.entry.remaining_amount,
            date: paymentDetailsStore.entry.date,
            notes: paymentDetailsStore.entry.notes ? paymentDetailsStore.entry.notes : null,
            payment_id: props.payment?.id,
        }
        let url = "/payments/create"
        if (props.isEdit) {
            url = `/payments/${props.payment.id}`
        }
        await api.send(url, props.isEdit ? "PATCH" : "POST", {}, body)
        isAddPaymentOpen.value = false
        paymentDetailsStore.isEdit = false
        isCreating.value = false
        if (props.reload) {
            props.reload()
        }
    } catch (error) {
        isCreating.value = false
        if (error.errors && error.status === 422) {
            paymentDetailsStore.errors = error.errors
        }
    }
}

watch(isAddPaymentOpen, () => {
    if (isAddPaymentOpen.value) {
        isCreating.value = false
        paymentDetailsStore.errors = {}
        if (props.patient) {
            paymentDetailsStore.entry.patient_id = props.patient.id
        }
        if (!props.isEdit) {
            paymentDetailsStore.entry.date = format(new Date(), "yyyy-MM-dd")
            paymentDetailsStore.entry.amount = null
            paymentDetailsStore.entry.notes = null
            paymentDetailsStore.entry.remaining_amount = props.payment?.remaining_amount
        } else {
            paymentDetailsStore.entry.date = props.payment?.date
            paymentDetailsStore.entry.amount = props.payment?.amount
            paymentDetailsStore.entry.notes = props.payment?.visit?.notes
            paymentDetailsStore.entry.remaining_amount = props.payment?.remaining_amount
        }
    }
})
</script>
