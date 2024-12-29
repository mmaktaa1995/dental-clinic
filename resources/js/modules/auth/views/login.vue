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

<script setup>
import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

// State variables
const email = ref("")
const password = ref("")
const errors = ref({})

// Router instance
const router = useRouter()

// Login method
const login = async () => {
    const data = { email: email.value, password: password.value }

    try {
        const response = await axios.post("/api/login", data)

        // Save user and token in localStorage
        localStorage.setItem("user", JSON.stringify(response.data.data.user))
        localStorage.setItem("access_token", response.data.data.access_token)

        // Update axios default authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.data.access_token}`

        // Navigate to the patients index page
        router.push({ name: "patients-index" })
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors
        }
    }
}
</script>
