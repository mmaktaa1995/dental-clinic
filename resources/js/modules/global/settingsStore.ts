import { defineStore } from "pinia"
import { useRouter } from "vue-router"
import { api } from "@/logic/api"

type UsdExchangeRate = {
    eur_aleppo: number
    eur_damas: number
    try_aleppo: number
    try_damas: number
    usd_aleppo: number
    usd_damas: number
}

export const useSettingsStore = defineStore("settings-store", {
    state: () => ({
        lastFileNumber: -1,
        exchangeRate: {} as UsdExchangeRate,
    }),
    actions: {
        async getExchangeRate() {
            this.exchangeRate = await api.get("/currencies/exchange-rate")
        },
        async getLastFileNumber() {
            const response = await api.get("/patients/lastFileNumber")
            this.lastFileNumber = response.last_file_number
        },
    },
})
