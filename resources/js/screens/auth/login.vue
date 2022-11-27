<template>
    <div class="w-full">
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <div class="flex-1 font-medium h-16 inline-flex items-center px-4 text-center text-xl">
                        <img class="w-56" src="/images/logo.png" alt="logo">
                    </div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        تسجيل الدخول الى حسابك
                    </h2>
                </div>
                <form class="mt-8 space-y-6" action="#" method="POST">

                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="email-address" class="sr-only">البريد الالكتروني</label>
                            <input id="email-address" name="email" v-model="email" type="email" autocomplete="email"
                                   required
                                   :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.email?'border-red-500':''}`"
                                   placeholder="البريد الالكتروني">
                            <small class="text-red-600 text-xs text-right block" v-if="errors && errors.email">{{
                                    errors.email[0]
                                }}</small>
                        </div>
                        <div>
                            <label for="password" class="sr-only">كلمة المرور</label>
                            <input id="password" name="password" v-model="password" type="password"
                                   autocomplete="current-password"
                                   required
                                   :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.password?'border-red-500':''}`"
                                   placeholder="كلمة المرور">
                            <small class="text-red-600 text-xs text-right block" v-if="errors && errors.password">{{
                                    errors.password[0]
                                }}</small>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-1">
                        <button type="submit" @click.prevent="login()"
                                class="group transition-all duration-100 relative col-span-6 sm:col-span-3 sm:pr-2 flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            تسجيل دخول
                            <span class="absolute left-0 pl-3 inset-y-0 flex items-center">
                                <!-- Heroicon name: solid/lock-closed -->
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"/>
                                </svg>
                              </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            errors: {},
            username: '',
            name: '',
            email: '',
            password: ''
        }
    },
    methods: {
        login() {
            let data = {
                email: this.email,
                password: this.password,
            }
            axios.post('/api/login', data).then(({data}) => {
                localStorage.setItem('user', JSON.stringify(data.data.user))
                localStorage.setItem('access_token', data.data.access_token)
                bus.$emit('flash-message', {text: data.message, type: 'success'})
                app.user = data.data.user;
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.data.access_token;
                this.$router.push({name: 'patients-index'})

            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors
                }
                bus.$emit('flash-message', {text: error.response.data.message, type: 'error'})
            })
        }
    }
}
</script>

