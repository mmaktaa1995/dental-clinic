<template>
    <CDialog v-model="opened" type="warning">
        <template #header>
            {{ $t(title) }}
        </template>
        <template #body>
            <div class="min-h-[40vh]">
                <div class="grid grid-cols-1 w-full gap-4">
                    <CAutocomplete v-model="appointmentsStore.entry.patient_id" v-model:object="appointmentsStore.entry.patient" :errors="appointmentsStore.errors" :label="$t('appointments.patient')" name="patient_id" :fetch-items="fetchPatientsData"></CAutocomplete>
                    <CDateTimePicker v-model="appointmentsStore.entry.date" :errors="appointmentsStore.errors" autocomplete="off" :label="$t('appointments.appointmentDate')" name="date" />
                    <CTextArea v-model="appointmentsStore.entry.notes" :errors="appointmentsStore.errors" autocomplete="off" :label="$t('appointments.notes')" name="notes" />
                </div>
            </div>
        </template>
        <template #actions>
            <CButton @click="back"> {{ $t("global.actions.cancel") }}</CButton>
            <c-async-button type="info" :loading="appointmentsStore.isLoading" @click="save">
                {{ $t("global.actions.confirm") }}
            </c-async-button>
            <CButton class="mr-auto ltr:ml-auto ltr:mr-0" type="error" @click="((appointmentsStore.isDeleteAppointmentOpened = true), (opened = false))">
                {{ $t("global.actions.delete") }}
            </CButton>
        </template>
    </CDialog>
</template>

<script setup lang="ts">
import { computed, nextTick, watch } from "vue"
import { api } from "@/logic/api"
import { AppointmentEntry, useAppointmentsStore } from "@/modules/appointments/store"
import { useToastStore } from "@/modules/global/toastStore"

const opened = defineModel<boolean>({ required: true })
const props = defineProps<{
    appointment?: AppointmentEntry
}>()

const appointmentsStore = useAppointmentsStore()
const toastStore = useToastStore()

const fetchPatientsData = async (page: number, searchQuery: string) => {
    return await api.get(`/patients/list?per_page=20&page=${page}&query=${searchQuery}`)
}

const back = () => {
    opened.value = false
}

const title = computed(() => {
    return !props.appointment.id ? "appointments.addAppointment" : "appointments.editAppointment"
})

const save = () => {
    if (props.appointment.id) {
        update()
    } else {
        create()
    }
}

const create = async () => {
    appointmentsStore.errors = {}
    appointmentsStore.isLoading = true

    try {
        const response = await api.post("/appointments", appointmentsStore.entry)
        toastStore.success(response.message)
        back()
        appointmentsStore.isLoading = false
        await appointmentsStore.getAppointments()
    } catch (error: any) {
        if (error.errors && error?.status === 422) {
            appointmentsStore.errors = error.errors
        }
    } finally {
        appointmentsStore.isLoading = false
    }
}

const update = async () => {
    appointmentsStore.errors = {}
    appointmentsStore.isLoading = true
    api.patch(`/appointments/${appointmentsStore.entry.id}`, appointmentsStore.entry)
        .then((response) => {
            back()
            toastStore.success(response.message)
            appointmentsStore.isLoading = false
            appointmentsStore.getAppointments()
        })
        .catch((error: any) => {
            if (error.errors && error.status === 422) {
                appointmentsStore.errors = error.errors
            }
        })
        .finally(() => {
            appointmentsStore.isLoading = false
        })
}

watch(opened, async () => {
    if (opened.value) {
        appointmentsStore.errors = {}
        appointmentsStore.entry = {
            id: undefined,
            patient: undefined,
            patient_id: null,
            date: appointmentsStore.entry.date,
            notes: "",
        }

        if (props.appointment.id) {
            const response = await api.get(`/appointments/${props.appointment.id}`)
            nextTick().then(() => {
                appointmentsStore.entry = response
            })
        }
    }
})
</script>
