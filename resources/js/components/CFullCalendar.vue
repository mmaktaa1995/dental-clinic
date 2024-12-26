<template>
    <FullCalendar v-if="loaded" :key="key" ref="fullCalendar" class="app-calendar" :options="calendarOptions">
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
import { computed, ref } from "vue"

const props = defineProps<{
    events: []
    loaded: boolean
}>()

const key = new Date().valueOf()

const $emits = defineEmits(["handleDateSelect", "handleEventClick", "handleEvents"])

const fullCalendar = ref(null)
const calendarOptions = computed(() => {
    return {
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
    }
})
</script>

<!--<style>-->
<!--/* General calendar container styling */-->
<!--.calendar-container {-->
<!--    background-color: #f9fafb;-->
<!--    padding: 1rem;-->
<!--    border-radius: 8px;-->
<!--    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);-->
<!--}-->

<!--/* Modern header styles */-->
<!--.fc-toolbar {-->
<!--    display: flex;-->
<!--    justify-content: space-between;-->
<!--    align-items: center;-->
<!--    padding: 0.5rem;-->
<!--    background-color: #ffffff;-->
<!--    border-bottom: 1px solid #e5e7eb;-->
<!--    border-radius: 8px 8px 0 0;-->
<!--}-->

<!--.fc-toolbar-title {-->
<!--    font-size: 1.25rem;-->
<!--    font-weight: 600;-->
<!--    color: #374151;-->
<!--}-->

<!--.fc-button {-->
<!--    background-color: #2563eb;-->
<!--    color: white;-->
<!--    border: none;-->
<!--    border-radius: 4px;-->
<!--    padding: 0.5rem 1rem;-->
<!--    font-size: 0.875rem;-->
<!--    cursor: pointer;-->
<!--    transition: background-color 0.2s;-->
<!--}-->

<!--.fc-button:hover {-->
<!--    background-color: #1d4ed8;-->
<!--}-->

<!--.fc-button:focus {-->
<!--    outline: 2px solid #2563eb;-->
<!--}-->

<!--/* Day grid styling */-->
<!--.fc-daygrid-day {-->
<!--    border: 1px solid #e5e7eb;-->
<!--    padding: 0.5rem;-->
<!--    background-color: #ffffff;-->
<!--}-->

<!--.fc-daygrid-day:hover {-->
<!--    background-color: #f3f4f6;-->
<!--}-->

<!--/* Event styling */-->
<!--.fc-event {-->
<!--    background-color: #2563eb;-->
<!--    color: white;-->
<!--    border: none;-->
<!--    padding: 0.25rem 0.5rem;-->
<!--    border-radius: 4px;-->
<!--    font-size: 0.75rem;-->
<!--}-->

<!--.fc-event:hover {-->
<!--    background-color: #1d4ed8;-->
<!--}-->

<!--/* Today's date styling */-->
<!--.fc-day-today {-->
<!--    background-color: #e0f2fe;-->
<!--    border: 1px solid #38bdf8;-->
<!--}-->
<!--</style>-->
<style scoped>
/* Base Tailwind colors and spacing are already used, scoped CSS tweaks for specific FullCalendar classes */
.fc-toolbar-title {
    @apply text-lg font-semibold text-gray-700; /* Improve title appearance */
}
.fc-button {
    @apply bg-indigo-600 text-white rounded-md py-1 px-3 text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2; /* Add hover and focus states */
}
.fc-button-disabled {
    @apply bg-gray-300 text-gray-500 cursor-not-allowed;
}
.fc-day-today {
    @apply bg-blue-50 border border-blue-500; /* Highlight current day */
}
.fc-event {
    @apply bg-indigo-500 text-white rounded-lg px-2 py-1 text-sm font-medium shadow-md hover:bg-indigo-600; /* Modern event design */
}
.fc-daygrid-day:hover {
    @apply bg-gray-100; /* Add hover effect on day cells */
}
.fc-day-past {
    @apply text-gray-400; /* De-emphasize past days */
}
.fc-day-sun {
    @apply text-pink-600; /* Highlight Sundays */
}
</style>
