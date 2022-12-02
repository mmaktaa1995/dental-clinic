<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import arLocale from '@fullcalendar/core/locales/ar';
import axios from "axios";

export default {

    components: {
        FullCalendar // make the <FullCalendar> tag available
    },

    data: function () {
        return {
            events: [],
            calendarOptions: {
                locale: arLocale,
                plugins: [
                    dayGridPlugin,
                    timeGridPlugin,
                    interactionPlugin // needed for dateClick
                ],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                initialEvents: [], // alternatively, use the `events` setting to fetch from a feed
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents
                /* you can update a remote database when these fire:
                eventAdd:
                eventChange:
                eventRemove:
                */,
                events: this.events,
                datesSet: event => {
                    var midDate = new Date((event.start.getTime() + event.end.getTime()) / 2)
                    var month = `0${midDate.getMonth() + 1}`
                    this.getData(+month, midDate.getFullYear())
                },
            },
            currentEvents: [],
            key: 10,
            year: '',
            month: ''
        }
    },
    mounted() {
        let self = this;
        bus.$on('appointment-changed', function () {
            self.getData(this.month, this.year);
        })
    },
    methods: {
        getData(month, year) {
            if (this.year !== year || this.month !== month) {
                this.year = year;
                this.month = month;
                axios.get(`/api/appointments?year=${year}&month=${month}`).then(({data}) => {
                    this.events = data.map(_ => {
                        return {
                            id: _.id,
                            title: _.patient.name,
                            description: _.notes,
                            className: _.className,
                            date: _.date.replace(/.(\d)*Z/g, ''),
                            start: _.start.replace(/.(\d)*Z/g, ''),
                            end: _.end.replace(/.(\d)*Z/g, '')
                        }
                    });
                    this.calendarOptions.events = this.events;
                })
            }
        },
        handleWeekendsToggle() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends // update a property
        },

        handleDateSelect(selectInfo) {
            this.$router.push({name: 'appointments-create', query: {date: selectInfo.startStr}});
        },

        handleEventClick(clickInfo) {
            this.$router.push({
                name: 'appointments-edit',
                params: {id: clickInfo.event.id},
                query: {isPast: clickInfo.event.isPast}
            });
        },

        handleEvents(events) {
            this.currentEvents = events
        }
    }
}
</script>

<template>
    <div class="px-16 py-8">
        <div class='demo-app-main'>
            <FullCalendar
                :key="key"
                ref="fullCalendar"
                class='demo-app-calendar'
                :options='calendarOptions'
            >
                <template v-slot:eventContent='arg'>
                    <b>{{ arg.timeText }}</b>
                    <i>{{ arg.event.title }}</i>
                </template>
            </FullCalendar>
        </div>
        <router-view></router-view>
    </div>
</template>

