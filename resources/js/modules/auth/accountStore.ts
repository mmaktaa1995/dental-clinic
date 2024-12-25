import { defineStore } from "pinia"
import axios from "axios"
import { useRouter } from "vue-router"
import { api } from "@/logic/api"

export type User = {
    id: number
    name: string
    email: string
    email_verified_at: string
    admin: boolean
    created_at: string
    updated_at: string
}

export const useAccountStore = defineStore("account-store", {
    state: () => ({
        user: {} as User,
        isLoggedIn: false,
        lastFileNumber: -1,
    }),
    actions: {
        async getUser() {
            const response = await api.get("/user")
            this.user = response
            this.isLoggedIn = true
        },
        async getLastFileNumber() {
            const response = await api.get("/patients/lastFileNumber")
            this.lastFileNumber = response.last_file_number
        },
        async logout() {
            if (!this.isLoggedIn) {
                return
            }
            const logoutUrl = `/logout${this.user.admin ? "-admin" : ""}`
            const router = useRouter()
            await api.post(logoutUrl).then(({ data }) => {
                localStorage.clear()
                router.replace({ name: "login" }).then(() => {
                    this.user = {} as User
                    this.isLoggedIn = false
                })
            })
        },
    },
})
