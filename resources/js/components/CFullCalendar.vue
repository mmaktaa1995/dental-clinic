<template>
    <FullCalendar :key="key" ref="fullCalendar" class="app-calendar" :options="calendarOptions">
        <template #eventContent="arg">
            <b>{{ arg.timeText }}</b>
            <i>{{ arg.event.title }}</i>
        </template>
    </FullCalendar>
</template>
<script setup lang="ts">
import FullCalendar from "@fullcalendar/vue3"
import arLocale from "@fullcalendar/core/locales/ar"
import dayGridPlugin from "@fullcalendar/daygrid"
import timeGridPlugin from "@fullcalendar/timegrid"
import interactionPlugin from "@fullcalendar/interaction"
import { ref } from "vue"

const props = defineProps<{
    events: []
}>()

const key = new Date().valueOf()

const $emits = defineEmits(["handleDateSelect", "handleEventClick", "handleEvents"])

const fullCalendar = ref(null)
const calendarOptions = ref({
    locale: arLocale,
    plugins: [
        dayGridPlugin,
        timeGridPlugin,
        interactionPlugin, // needed for dateClick
    ],
    headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    initialView: "dayGridMonth",
    initialEvents: [], // alternatively, use the `events` setting to fetch from a feed
    editable: true,
    selectable: true,
    selectMirror: true,
    dayMaxEvents: true,
    weekends: true,
    select: (event) => {
        $emits("handleDateSelect", event)
    },
    eventClick: (event) => {
        $emits("handleEventClick", event)
    },
    eventsSet: (event) => {
        $emits("handleEvents", event)
    },
    /* you can update a remote database when these fire:
    eventAdd:
    eventChange:
    eventRemove:
    */
    events: props.events,
    // datesSet: (event) => {
    //     const midDate = new Date((event.start.getTime() + event.end.getTime()) / 2)
    //     const month = `0${midDate.getMonth() + 1}`
    //     this.getData(+month, midDate.getFullYear())
    // },
})
</script>
