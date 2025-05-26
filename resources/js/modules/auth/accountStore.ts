import { defineStore } from "pinia"
import { useRouter } from "vue-router"
import { api } from "@/logic/api"

export type Permission = {
    id: number
    name: string
    slug: string
}

export type Role = {
    id: number
    name: string
    slug: string
    permissions?: Permission[]
}

export type User = {
    id: number
    name: string
    email: string
    email_verified_at: string
    admin: boolean
    created_at: string
    updated_at: string
    roles?: Role[]
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
            const response = await api.get("/user")
            this.user = response.data
            this.isLoggedIn = true
            return response
        },
        
        async resendVerificationEmail() {
            try {
                const response = await api.post("/email/resend")
                return { success: true, message: response.data.message || 'Verification link sent!' }
            } catch (error: any) {
                console.error('Error resending verification email:', error)
                return { 
                    success: false, 
                    message: error.response?.data?.message || 'Failed to send verification email' 
                }
            }
        },
        async login(credentials: { email: string; password: string }) {
            try {
                // First, get the CSRF cookie
                await fetch('/api/v1/sanctum/csrf-cookie', { credentials: 'include' })
                
                // Then attempt to login
                const response = await api.post("/login", credentials)
                this.user = response.data.user
                localStorage.setItem("user", JSON.stringify(response.data.user))
                localStorage.setItem("access_token", response.data.access_token)
                this.isLoggedIn = true
                
                // Return verification status
                return {
                    success: true,
                    email_verified: !!response.data.user.email_verified_at,
                    user: response.data.user
                }
            } catch (error: any) {
                // Handle verification required error
                if (error.response?.status === 403 && error.response?.data?.verification_required) {
                    return {
                        success: false,
                        verification_required: true,
                        message: error.response.data.message
                    }
                }
                
                // Re-throw other errors
                throw error
            }
        },
        async logout() {
            if (!this.isLoggedIn) {
                return
            }
            const logoutUrl = `/logout`
            await api.post(logoutUrl).then(() => {
                localStorage.clear()
                this.user = {} as User
                this.isLoggedIn = false
            })
        },
    },
})
