import { defineStore } from "pinia"
import axios from "axios"

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
    }),
    actions: {
        async getUser() {
            const { data } = await axios.get("/api/user")
            this.user = data
            console.log(this.user)
        },
    },
})
