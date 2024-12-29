import { defineStore } from "pinia"
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
type UsdExchangeRate = {
    usd_aleppo: number
}

export const useAccountStore = defineStore("account-store", {
    state: () => ({
        user: {} as User,
        isLoggedIn: false,
    }),
    actions: {
        async getUser() {
            this.user = await api.get("/user")
            this.isLoggedIn = true
        },
        async logout() {
            if (!this.isLoggedIn) {
                return
            }
            const logoutUrl = `/logout${this.user.admin ? "-admin" : ""}`
            const router = useRouter()
            await api.post(logoutUrl).then(() => {
                localStorage.clear()
                router.replace({ name: "login" }).then(() => {
                    this.user = {} as User
                    this.isLoggedIn = false
                })
            })
        },
    },
})
