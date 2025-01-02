<template>
    <div class="px-16 py-8 w-full">
        <div>
            <CFullCalendar ref="fullCalendar" :loaded="loaded" :events="events" @handle-date-select="handleDateSelect" @handle-event-click="handleEventClick" @handle-events="handleEvents">
                <template #eventContent="arg">
                    <b>{{ arg.timeText }}</b>
                    <i>{{ arg.event.title }}</i>
                </template>
            </CFullCalendar>
        </div>
        <router-view></router-view>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"
import { api } from "@/logic/api"

const events = ref([])
const currentEvents = ref([])
const year = ref("")
const month = ref("")
const loaded = ref(false)
const router = useRouter()

const getData = async (monthParam, yearParam) => {
    if (year.value !== yearParam || month.value !== monthParam) {
        loaded.value = false
        year.value = yearParam
        month.value = monthParam

        const { data } = await api.get(`/appointments?year=${yearParam}&month=${monthParam}`)
        events.value = data.map((appointment) => ({
            id: appointment.id,
            title: appointment.patient.name,
            description: appointment.notes,
            className: appointment.className,
            date: appointment.date,
            start: appointment.start,
            end: appointment.end,
        }))
        loaded.value = true
    }
}

const handleDateSelect = (selectInfo) => {
    router.push({ name: "appointments-create", query: { date: selectInfo.startStr } })
}

const handleEventClick = (clickInfo) => {
    router.push({
        name: "appointments-edit",
        params: { id: clickInfo.event.id },
        query: { isPast: clickInfo.event.extendedProps.isPast },
    })
}

const handleEvents = (eventsData) => {
    currentEvents.value = eventsData
}

onMounted(() => {
    const today = new Date()
    getData(today.getMonth() + 1, today.getFullYear()).then()
    // window.bus.$on("appointment-changed", () => {
    // })
})
</script>
