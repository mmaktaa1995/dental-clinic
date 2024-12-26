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
import { formatISO, parseISO } from "date-fns"
import { useRouter } from "vue-router"

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

        const { data } = await axios.get(`/api/appointments?year=${yearParam}&month=${monthParam}`)
        events.value = data.map((appointment) => ({
            id: appointment.id,
            title: appointment.patient.name,
            description: appointment.notes,
            className: appointment.className,
            date: formatISO(parseISO(appointment.date), { representation: "date" }),
            start: formatISO(parseISO(appointment.start), { representation: "time" }),
            end: formatISO(parseISO(appointment.end), { representation: "time" }),
        }))
        loaded.value = true
        console.log(events.value, data)
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
    console.log(today)
    getData(today.getMonth() + 1, today.getFullYear()).then()
    // window.bus.$on("appointment-changed", () => {
    // })
})
</script>
