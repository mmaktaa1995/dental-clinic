<template>
    <div class="w-full">
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <div class="flex-1 font-medium h-16 inline-flex items-center px-4 text-center text-xl">
                        <img class="w-56" src="/images/logo.png" alt="logo" />
                    </div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-700">تسجيل الدخول الى حسابك</h2>
                </div>
                
                <!-- Success message for verified email -->
                <div v-if="emailVerified" class="rounded-md bg-green-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ $t('auth.verifyEmail.emailVerified', 'تم التحقق من البريد الإلكتروني بنجاح.') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Error message for verification required -->
                <div v-if="verificationRequired" class="rounded-md bg-yellow-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">
                                {{ $t('auth.verifyEmail.pleaseVerifyEmail', 'يرجى التحقق من بريدك الإلكتروني قبل الاستمرار.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <form class="mt-8 space-y-6" @submit.prevent="login">
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="email-address" class="sr-only">البريد الالكتروني</label>
                            <input
                                id="email-address"
                                v-model="email"
                                type="email"
                                autocomplete="email"
                                required
                                :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-700 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors.email ? 'border-red-500' : ''}`"
                                placeholder="البريد الالكتروني"
                            />
                            <small v-if="errors.email" class="text-rose-600 text-xs text-right block">{{ errors.email[0] }}</small>
                        </div>
                        <div>
                            <label for="password" class="sr-only">كلمة المرور</label>
                            <input
                                id="password"
                                v-model="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-700 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors.password ? 'border-red-500' : ''}`"
                                placeholder="كلمة المرور"
                            />
                            <small v-if="errors.password" class="text-rose-600 text-xs text-right block">{{ errors.password[0] }}</small>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-1">
                        <button
                            type="submit"
                            class="group transition-all duration-100 relative col-span-6 sm:col-span-3 sm:pr-2 flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            تسجيل دخول
                            <span class="absolute left-0 pl-3 inset-y-0 flex items-center">
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue"
import { useRouter, useRoute } from "vue-router"
import { useAccountStore } from "@/modules/auth/accountStore"
import { useSettingsStore } from "@/modules/global/settingsStore"
import { useI18n } from "vue-i18n"

const email = ref("")
const password = ref("")
const errors = ref<Record<string, string[]>>({})
const emailVerified = ref(false)
const verificationRequired = ref(false)

const router = useRouter()
const route = useRoute()
const { t } = useI18n()
const accountStore = useAccountStore()
const settingsStore = useSettingsStore()

const login = async () => {
    const data = { email: email.value, password: password.value }
    errors.value = {}
    verificationRequired.value = false

    try {
        await accountStore.login(data)
        await accountStore.getUser()

        // Check if email is verified
        if (!accountStore.user?.email_verified_at) {
            // Store login credentials for verification page
            localStorage.setItem('pendingVerification', JSON.stringify({
                email: email.value,
                password: password.value
            }))
            
            verificationRequired.value = true
            
            // Redirect to verification page after a short delay
            setTimeout(() => {
                router.push({ name: "verify-email" })
            }, 2000)
            return
        }

        // Navigate to the patients index page
        router.push({ name: "patients-index" }).then(async () => {
            await settingsStore.getExchangeRate()
            await settingsStore.getLastFileNumber()
            await settingsStore.getTeeth()
        })
    } catch (error) {
        if (error.response?.data?.errors && error.response?.status === 422) {
            errors.value = error.response.data.errors
        } else if (error.response?.status === 403 && error.response?.data?.verification_required) {
            verificationRequired.value = true
            
            // Store login credentials for verification page
            localStorage.setItem('pendingVerification', JSON.stringify({
                email: email.value,
                password: password.value
            }))
            
            // Redirect to verification page after a short delay
            setTimeout(() => {
                router.push({ name: "verify-email" })
            }, 2000)
        }
    }
}

// Check URL parameters on component mount
onMounted(() => {
    // Check if user just verified their email
    if (route.query.verified === '1') {
        emailVerified.value = true
    }
    
    // Check if verification is required
    if (route.query.verification_required === '1') {
        verificationRequired.value = true
    }
})
</script>
