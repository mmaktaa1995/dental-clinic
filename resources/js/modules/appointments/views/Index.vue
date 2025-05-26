<template>
    <div class="px-16 py-8 w-full">
        <div class="mb-4">
            <div class="flex justify-end">
                <ImportExportButtons 
                    model-type="appointments" 
                    :filters="exportFilters"
                    @import-complete="handleImportComplete"
                />
            </div>
        </div>
        <div>
            <CFullCalendar :fetch-events="fetchEvents" :loaded="!appointmentsStore.isLoading" :events="appointmentsStore.entries" @handle-date-select="handleDateSelect" @handle-event-click="handleEventClick" @handle-events="handleEvents" @handle-dates-set="handleDatesSet"> </CFullCalendar>
        </div>

        <CConfirmModal
            v-model="appointmentsStore.isDeleteAppointmentOpened"
            v-model:loading="appointmentsStore.isLoading"
            :confirm-title="
                $t('global.deleteEntryTitle', {
                    type: $t('appointments.appointment'),
                })
            "
            :confirm-body-message="
                $t('global.deleteEntryBodyMessage', {
                    type: $t('appointments.appointment'),
                })
            "
            @confirm-callback="deleteAppointment"
        >
        </CConfirmModal>

        <EditGeneral v-model="isEditAppointmentOpened" :appointment="appointmentsStore.entry"></EditGeneral>
        <router-view></router-view>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue"
import { useAppointmentsStore } from "@/modules/appointments/store"
import { api } from "@/logic/api"
import EditGeneral from "@/modules/appointments/views/EditGeneral.vue"
import ImportExportButtons from "@/components/ImportExportButtons.vue"
import { DateSelectArg, EventApi, EventClickArg } from "@fullcalendar/core"
import { format, isFriday } from "date-fns"
import { parseDate } from "@/logic/helpers"
import { useToastStore } from "@/modules/global/toastStore"

const events = ref([])
const loadingEvents = ref(false)
const appointmentsStore = useAppointmentsStore()
const currentEvents = ref([])
const isEditAppointmentOpened = ref(false)
const toastStore = useToastStore()

// Computed property to provide current filters to export component
const exportFilters = computed(() => ({
    start_date: appointmentsStore.startDate,
    end_date: appointmentsStore.endDate
}))

const handleImportComplete = (result: { success: boolean; message: string }) => {
    if (result.success) {
        toastStore.success(result.message)
        // Refresh the appointments after import
        getData(appointmentsStore.startDate, appointmentsStore.endDate)
    } else {
        toastStore.error(result.message)
    }
}

const getData = async (startDate, endDate) => {
    appointmentsStore.startDate = startDate
    appointmentsStore.endDate = endDate

    const response = await appointmentsStore.getAppointments()
    events.value = response
}

const handleDateSelect = (selectInfo: DateSelectArg) => {
    if (isFriday(new Date(selectInfo.startStr))) {
        return
    }
    let startDate = selectInfo.startStr
    if (selectInfo.view.type === "dayGridMonth") {
        startDate += " 00:00:00"
    } else {
        startDate = format(parseDate(startDate), "yyyy-MM-dd hh:mm:ss")
    }
    appointmentsStore.entry = {
        date: startDate,
    }
    isEditAppointmentOpened.value = true
}

const handleEventClick = (clickInfo: EventClickArg) => {
    appointmentsStore.entry = appointmentsStore.entries.find((appointment) => +clickInfo.event.id === +appointment.id)
    isEditAppointmentOpened.value = true
}

const handleEvents = (eventsData: EventApi[]) => {
    currentEvents.value = eventsData
}

const handleDatesSet = async (event) => {
    if (loadingEvents.value) {
        return
    }
    loadingEvents.value = true
    await getData(event.startStr, event.endStr)
    loadingEvents.value = false
}

const fetchEvents = async (event) => {
    await handleDatesSet(event)
}

const deleteAppointment = () => {
    appointmentsStore.isLoading = true
    api.delete(`/appointments/${appointmentsStore.entry.id}`)
        .then(() => {
            appointmentsStore.getAppointments(year.value, month.value)
        })
        .finally(() => {
            appointmentsStore.isLoading = false
        })
}
</script>
