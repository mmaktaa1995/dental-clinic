import { defineStore } from "pinia"
import { api } from "@/logic/api"

type UsdExchangeRate = {
    eur_aleppo: number
    eur_damas: number
    try_aleppo: number
    try_damas: number
    usd_aleppo: number
    usd_damas: number
}

type ToothEntry = {
    id: number
    name: string
    image: string
    number: number
    extra: {
        musculature: string
        glands: string
        meridian: string
        senseorgan: string
        details: string
        timage_x: string
        text_y: string
        timage_y: string
        text_x: string
    }
}

export const useSettingsStore = defineStore("settings-store", {
    state: () => ({
        lastFileNumber: -1,
        exchangeRate: {} as UsdExchangeRate,
        teeth: [] as ToothEntry[],
    }),
    actions: {
        async getExchangeRate() {
            this.exchangeRate = await api.get("/currencies/exchange-rate")
        },
        async getLastFileNumber() {
            const response = await api.get("/patients/lastFileNumber")
            this.lastFileNumber = response.last_file_number
        },
        async getTeeth() {
            this.teeth = await api.get("/teeth")
        },
    },
})
