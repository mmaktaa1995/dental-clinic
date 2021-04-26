<template>
    <div class="w-full">
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <div class="flex-1 font-medium h-16 inline-flex items-center px-4 text-center text-xl">
                        <svg viewBox="0 0 40 40" class="h-24 w-24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M4.345 9h10.55L9.618 20 4.345 9zm21.099 0h10.55l-5.276 11-5.274-11z" fill="#E9F9FD"
                                  fill-opacity=".1"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.62 20h10.549l-5.275 11L9.62 20z"
                                  fill="#25C4F2" fill-opacity=".22"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.169 20h10.55l-5.275 11-5.275-11z"
                                  fill="#25C4F2" fill-opacity=".2"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.169 20H9.619l5.275-11 5.275 11z"
                                  fill="#25C4F2" fill-opacity=".4"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M30.718 20h-10.55l5.276-11 5.274 11z"
                                  fill="#25C4F2" fill-opacity=".4"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M25.444 31h-10.55l5.275-11 5.275 11z"
                                  fill="#25C4F2" fill-opacity=".5"/>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M3.494 8.467A1 1 0 0 1 4.34 8h10.55a1 1 0 0 1 .902.568l4.373 9.12 4.373-9.12A1 1 0 0 1 25.44 8h10.55a1 1 0 0 1 .902 1.432L26.345 31.424a1.001 1.001 0 0 1-.905.576H14.89a1 1 0 0 1-.902-.568l-10.55-22a1 1 0 0 1 .056-.965zm21.95 2.846L29.13 19h-7.372l3.686-7.687zM5.934 10l3.686 7.687L13.306 10H5.933zm8.96 1.313L18.58 19h-7.372l3.686-7.687zM27.032 10l3.686 7.687L34.405 10h-7.373zm-1.588 18.687L21.758 21h7.372l-3.686 7.687zM23.855 30l-3.686-7.687L16.483 30h7.372zm-8.96-1.313L11.207 21h7.372l-3.686 7.687z"
                                  fill="#25C4F2"/>
                        </svg>
                        <div class="ml-2text-center text-3xl font-extrabold text-gray-900">MWS AMW</div>
                    </div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Sign in to your account
                    </h2>
                </div>
                <form class="mt-8 space-y-6" action="#" method="POST">

                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="email-address" class="sr-only">Email address</label>
                            <input id="email-address" name="email" v-model="email" type="email" autocomplete="email"
                                   required
                                   :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.email?'border-red-500':''}`"
                                   placeholder="Email address">
                            <small class="text-red-600 text-xs" v-if="errors && errors.email">{{
                                    errors.email[0]
                                }}</small>
                        </div>
                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input id="password" name="password" v-model="password" type="password"
                                   autocomplete="current-password"
                                   required
                                   :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.password?'border-red-500':''}`"
                                   placeholder="Password">
                            <small class="text-red-600 text-xs" v-if="errors && errors.password">{{
                                    errors.password[0]
                                }}</small>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-1">
                        <button type="submit" @click.prevent="login('student')"
                                class="group transition-all duration-100 relative col-span-6 sm:col-span-3 sm:pr-2 flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <!-- Heroicon name: solid/lock-closed -->
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"/>
                                </svg>
                              </span>
                            Sign in as student
                        </button>
                        <button type="submit" @click.prevent="login('api')"
                                class="group transition-all duration-100 relative col-span-6 sm:col-span-3 sm:pl-2 flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <!-- Heroicon name: solid/lock-closed -->
                                <svg class="h-5 w-5 text-teal-500 group-hover:text-teal-400"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"/>
                                </svg>
                              </span>
                            Sign in as admin
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
        login(type) {
            let data = {
                email: this.email,
                password: this.password,
                type: type,
            }
            axios.post('/api/login', data).then(({data}) => {
                localStorage.setItem('user', JSON.stringify(data.data.user))
                localStorage.setItem('access_token', data.data.access_token)
                bus.$emit('flash-message', {text: data.message, type: 'success'})
                app.user = data.data.user;
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.data.access_token;
                if (type === 'api')
                    this.$router.push({name: 'courses-index'})
                else
                    this.$router.push({name: 'students-my-courses'})

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

