<template>
    <div class="w-full">
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <div class="flex-1 font-medium h-16 inline-flex items-center px-4 text-center text-xl">
                        <svg style="transform: rotate(180deg)" viewBox="0 0 40 40" class="h-24 w-24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                        <div class="ml-2text-center text-3xl font-extrabold text-gray-900">Aktaa Dental</div>
                    </div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Register new account
                    </h2>
                </div>
                <form class="mt-8 space-y-6" action="#" method="POST" @submit.prevent="register()">
                    <nav class="flex flex-col sm:flex-row">
                        <label
                            :class="`text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none w-1/2 text-center duration-75 cursor-pointer border-b-2 ${type==='api'?'text-blue-500 font-bold border-blue-500':'font-medium border-gray-200'}`">
                            Admin
                            <input type="radio" name="type" v-model="type" value="api" class="hidden">
                        </label>
                        <label
                            :class="`text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none w-1/2 text-center duration-75 cursor-pointer border-b-2 ${type==='student'?'text-blue-500 font-bold border-blue-500':'font-medium border-gray-200'}`">
                            Student
                            <input type="radio" name="type" v-model="type" value="student" class="hidden">
                        </label>
                    </nav>
                    <transition
                        name="fade"
                        mode="out-in"
                        appear
                        :duration="500"
                    >
                        <div :key="type+'-api'" class="rounded-md shadow-sm -space-y-px" v-if="type==='api'">
                            <div>
                                <label for="username" class="sr-only">Username</label>
                                <input id="username" name="username" v-model="username" type="text"
                                       autocomplete="username"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.username?'border-red-500':''}`"
                                       placeholder="Username">
                                <small class="text-red-600 text-xs" v-if="errors && errors.username">{{
                                        errors.username[0]
                                    }}</small>
                            </div>
                            <div>
                                <label for="name" class="sr-only">Name</label>
                                <input id="name" name="name" v-model="name" type="text" autocomplete="name"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.name?'border-red-500':''}`"
                                       placeholder="Name">
                                <small class="text-red-600 text-xs" v-if="errors && errors.name">{{
                                        errors.name[0]
                                    }}</small>
                            </div>
                            <div>
                                <label for="email-address" class="sr-only">Email address</label>
                                <input id="email-address" name="email" v-model="email" type="email" autocomplete="email"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.email?'border-red-500':''}`"
                                       placeholder="Email address">
                                <small class="text-red-600 text-xs" v-if="errors && errors.email">{{
                                        errors.email[0]
                                    }}</small>
                            </div>
                            <div>
                                <label for="password" class="sr-only">Password</label>
                                <input id="password" name="password" v-model="password" type="password"
                                       autocomplete="current-password"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.password?'border-red-500':''}`"
                                       placeholder="Password">
                                <small class="text-red-600 text-xs" v-if="errors && errors.password">{{
                                        errors.password[0]
                                    }}</small>
                            </div>
                        </div>
                        <div :key="type+'-student'" class="rounded-md shadow-sm -space-y-px" v-if="type==='student'">
                            <div>
                                <label for="username-student" class="sr-only">Username</label>
                                <input id="username-student" name="username" v-model="username" type="text"
                                       autocomplete="username"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.username?'border-red-500':''}`"
                                       placeholder="Username">
                                <small class="text-red-600 text-xs" v-if="errors && errors.username">{{
                                        errors.username[0]
                                    }}</small>
                            </div>
                            <div>
                                <label for="firstName" class="sr-only">First Name</label>
                                <input id="firstName" name="firstName" v-model="firstName" type="text"
                                       autocomplete="firstName"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.first_name?'border-red-500':''}`"
                                       placeholder="First Name">
                                <small class="text-red-600 text-xs"
                                       v-if="errors && errors.first_name">{{ errors.first_name[0] }}</small>
                            </div>
                            <div>
                                <label for="lastName" class="sr-only">Last Name</label>
                                <input id="lastName" name="lastName" v-model="lastName" type="text"
                                       autocomplete="lastName"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.last_name?'border-red-500':''}`"
                                       placeholder="Last Name">
                                <small class="text-red-600 text-xs"
                                       v-if="errors && errors.last_name">{{ errors.last_name[0] }}</small>
                            </div>
                            <div>
                                <label for="email-address-student" class="sr-only">Email address</label>
                                <input id="email-address-student" name="email" v-model="email" type="email"
                                       autocomplete="email"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.email?'border-red-500':''}`"
                                       placeholder="Email address">
                                <small class="text-red-600 text-xs"
                                       v-if="errors && errors.email">{{ errors.email[0] }}</small>
                            </div>
                            <div>
                                <label for="reg_year-student" class="sr-only">Reg Year</label>
                                <input id="reg_year-student" name="email" v-model="reg_year" type="number"
                                       autocomplete="reg_year"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.reg_year?'border-red-500':''}`"
                                       placeholder="Reg Year">
                                <small class="text-red-600 text-xs"
                                       v-if="errors && errors.reg_year">{{ errors.reg_year[0] }}</small>
                            </div>
                            <div>
                                <label for="address-student" class="sr-only">Address</label>
                                <input id="address-student" name="address" v-model="address" type="text"
                                       autocomplete="address"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.address?'border-red-500':''}`"
                                       placeholder="Address">
                                <small class="text-red-600 text-xs"
                                       v-if="errors && errors.address">{{ errors.address[0] }}</small>
                            </div>
                            <div>
                                <label for="mobile_number-student" class="sr-only">Mobile Number</label>
                                <input id="mobile_number-student" name="mobile_number" v-model="mobile_number"
                                       type="tel"
                                       autocomplete="mobile_number"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.mobile_number?'border-red-500':''}`"
                                       placeholder="Mobile Number">
                                <small class="text-red-600 text-xs"
                                       v-if="errors && errors.mobile_number">{{ errors.mobile_number[0] }}</small>
                            </div>
                            <div>
                                <label for="password-student" class="sr-only">Password</label>
                                <input id="password-student" name="password" v-model="password" type="password"
                                       autocomplete="current-password"
                                       :class="`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm ${errors && errors.password?'border-red-500':''}`"
                                       placeholder="Password">
                                <small class="text-red-600 text-xs" v-if="errors && errors.password">{{
                                        errors.password[0]
                                    }}</small>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mt-2">Gender</label>
                                <div class="grdi grid-cols-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-5 w-5 text-indigo-600" name="gender"
                                               value="male" v-model="gender"><span
                                        class="ml-2 text-gray-700">Male</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-5 w-5 text-indigo-600" name="gender"
                                               value="female" v-model="gender"><span
                                        class="ml-2 text-gray-700">Female</span>
                                    </label>
                                </div>
                                <small class="text-red-600 text-xs"
                                       v-if="errors && errors.gender">{{ errors.gender[0] }}</small>
                            </div>

                        </div>
                    </transition>
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                            Sign up
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
            type: 'api',
            username: '',
            firstName: '',
            lastName: '',
            reg_year: '',
            address: '',
            mobile_number: '',
            gender: '',
            name: '',
            email: '',
            password: ''
        }
    },
    methods: {
        register() {
            this.errors = {};
            let data = {
                username: this.username,
                name: this.name,
                email: this.email,
                password: this.password,
                type: this.type,
            }
            if (this.type === 'student') {
                data.first_name = this.firstName;
                data.last_name = this.lastName;
                data.reg_year = this.reg_year;
                data.address = this.address;
                data.mobile_number = this.mobile_number;
                data.gender = this.gender;
                delete data.name;
            }
            axios.post('/api/register', data).then(({data}) => {
                localStorage.setItem('user', JSON.stringify(data.data.user))
                localStorage.setItem('access_token', data.data.access_token)
                bus.$emit('flash-message', {text: data.message, type: 'success'})
                app.user = data.data.user;
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.data.access_token;
                if (this.type === 'api')
                    this.$router.push({name: 'courses-index'})
                else
                    this.$router.push({name: 'students-my-courses'})
            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors
                }
                bus.$emit('flash-message', {text: error.message, type: 'error'})
            })
        }
    }
}
</script>

<style scoped>

.fade-enter-active > *,
.fade-leave-active > * {
    transition-duration: 200ms;
    transition-property: opacity, transform;
    transition-timing-function: cubic-bezier(0.6, 0.15, 0.35, 0.8);
}

.fade-enter > *,
.fade-leave-to > * {
    opacity: 0;
    transform: translateY(40px);
}

.fade-enter-active > *:nth-child(2) {
    transition-delay: 100ms;
}

.fade-enter-active > *:nth-child(3) {
    transition-delay: 200ms;
}

.fade-leave-active > *:nth-child(1) {
    transition-delay: 200ms;
}

.fade-leave-active > *:nth-child(2) {
    transition-delay: 100ms;
}
</style>
