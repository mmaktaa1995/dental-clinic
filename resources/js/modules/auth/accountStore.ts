import { defineStore } from "pinia"
import axios from "axios"
import { useRouter } from "vue-router"

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
            const { data } = await axios.get("/api/user")
            this.user = data
            this.isLoggedIn = true
        },
        async getLastFileNumber() {
            const { data } = await axios.get("/api/patients/lastFileNumber")
            this.lastFileNumber = data.last_file_number
        },
        async logout() {
            if (!this.isLoggedIn) {
                return
            }
            const logoutUrl = `/api/logout${this.user.admin ? "-admin" : ""}`
            const router = useRouter()
            await axios.post(logoutUrl).then(({ data }) => {
                localStorage.clear()
                router.replace({ name: "login" }).then(() => {
                    this.user = {} as User
                    this.isLoggedIn = false
                })
            })
        },
    },
})
