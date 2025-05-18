<template>
    <c-container>
        <!--        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">-->
        <!--            <div class="">-->
        <!--                <label class="block text-sm font-medium text-gray-700 text-right">{{ $t("payments.totalRemainingAmount") }}</label>-->
        <!--                <label class="block text-2xl font-medium text-rose-600 text-right">{{ formattedValue(patientDebitsStore.totalRemainingPayments) }}</label>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <hr class="my-4" />-->
        <div>
            <PaymentsTable :store="patientDebitsStore" />
        </div>
    </c-container>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import PaymentsTable from "@/modules/payments/components/PaymentsTable.vue"
import { formatNumber } from "@/logic/helpers"
import { usePatientDetailsStore } from "@/modules/patients/detailStore"
import { usePatientDebitsStore } from "@/modules/patients/debitsStore"

const patientDebitsStore = usePatientDebitsStore()
const patientDetailsStore = usePatientDetailsStore()

const formattedValue = (value: number) => {
    return formatNumber(value)
}

useEntryListUpdater(`/debits/${patientDetailsStore.entryId}/patients`, patientDebitsStore, async (response) => {
    patientDebitsStore.totalRemainingPayments = response.totalDebits
})
</script>
