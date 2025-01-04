import { defineStore } from "pinia"
import { api } from "@/logic/api"

export type AppointmentEntry = {
    id?: number
    patient_id: number | null
    patient?: {
        name: string
        id: number
    }
    date: string
    start?: string
    end?: string
    notes: string
    isPast?: boolean
    className?: string
}

export const useAppointmentsStore = defineStore("appointments-store", {
    state: () => ({
        entries: [] as AppointmentEntry[],
        isLoading: false,
        isDeleteAppointmentOpened: false,
        entry: {} as AppointmentEntry,
        errors: {},
        startDate: "",
        endDate: "",
    }),
    actions: {
        async getAppointments() {
            if (this.isLoading) {
                return
            }
            this.isLoading = true

            const response = await api.get(`/appointments?startDate=${this.startDate}&endDate=${this.endDate}`)
            this.isLoading = false
            this.entries = [
                ...this.entries,
                ...response.map((appointment) => ({
                    id: appointment.id,
                    title: appointment.patient.name,
                    description: appointment.notes,
                    className: appointment.className,
                    date: appointment.date,
                    start: appointment.start,
                    end: appointment.end,
                })),
            ]
            return this.entries
        },
    },
})
