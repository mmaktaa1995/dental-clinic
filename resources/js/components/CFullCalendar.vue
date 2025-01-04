<template>
    <FullCalendar ref="fullCalendar" class="app-calendar" :options="calendarOptions">
        <template #eventContent="arg">
            <b>{{ arg.timeText }}</b>
            <span class="mr-2 ltr:mr-0 ltr:ml-2">{{ arg.event.title }}</span>
        </template>
    </FullCalendar>
</template>
<script setup lang="ts">
import FullCalendar from "@fullcalendar/vue3"
import arLocale from "@fullcalendar/core/locales/ar"
import enLocale from "@fullcalendar/core/locales/en-gb"
import deLocale from "@fullcalendar/core/locales/de"
import dayGridPlugin from "@fullcalendar/daygrid"
import timeGridPlugin from "@fullcalendar/timegrid"
import interactionPlugin from "@fullcalendar/interaction"
import { computed, ref } from "vue"
import { getSelectedLanguage } from "@/logic/i18n"

const props = defineProps<{
    events: []
    fetchEvents: (event) => any
    loaded: boolean
}>()

const $emits = defineEmits(["handleDateSelect", "handleEventClick", "handleEvents", "handleDatesSet"])

const fullCalendar = ref(null)

const locale = computed(() => {
    // arLocale.monthNames = ["كانون الثاني", "شباط", "آذار", "نيسان", "أيار", "حزيران", "تموز", "آب", "أيلول", "تشرين الأول", "تشرين الثاني", "كانون الأول"]
    let locale = arLocale

    switch (getSelectedLanguage().value) {
        case "de":
            locale = deLocale
            break
        case "en":
            locale = enLocale
            break
    }
    return locale
})

const calendarOptions = computed(() => {
    return {
        locale: locale.value,
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
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        eventTimeFormat: {
            hour: "2-digit",
            minute: "2-digit",
            meridiem: false,
        },
        select: (event) => {
            $emits("handleDateSelect", event)
        },
        eventClick: (event) => {
            $emits("handleEventClick", event)
        },
        eventsSet: (event) => {
            $emits("handleEvents", event)
        },
        events: props.events,
        datesSet: (event) => {
            props.fetchEvents(event)
        },
    }
})
</script>

<style>
/* Base Tailwind colors and spacing are already used, scoped CSS tweaks for specific FullCalendar classes */
.fc-button-disabled {
    @apply bg-gray-300 text-gray-500 cursor-not-allowed;
}
.fc-day-today {
    @apply !bg-blue-100; /* Highlight current day */
}
.fc-event {
    @apply bg-sky-500 text-white rounded-lg px-2 py-1 text-sm font-medium shadow-md hover:bg-sky-600; /* Modern event design */
}
.fc-day-past .fc-event {
    @apply bg-rose-500  hover:bg-rose-600; /* Modern event design */
}
.fc-day-future .fc-event {
    @apply bg-teal-500  hover:bg-teal-600; /* Modern event design */
}
.fc-daygrid-day {
    @apply cursor-pointer;
}
.fc-daygrid-day:hover {
    @apply !bg-blue-50; /* Add hover effect on day cells */
}
.fc-day-past {
    @apply text-gray-400; /* De-emphasize past days */
}

.fc-day-fri {
    @apply text-rose-600 pointer-events-none; /* Highlight Friday */
}
</style>
