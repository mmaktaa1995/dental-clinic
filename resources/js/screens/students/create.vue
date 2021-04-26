<template>
    <div
        :class="`fixed z-10 inset-0 overflow-y-auto `"
        aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div
                :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened?'bg-opacity-75':'bg-opacity-0'}`"
                @click="back()"
                aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!--
              Modal panel, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->

            <div
                :class="`inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full duration-200  ${opened?'scale-100':'scale-0'}` ">

                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300">
                    <h3 class="font-bold text-lg text-gray-700">Add Student</h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">

                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" id="username" autocomplete="title" v-model="username"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.username">{{ errors.username[0] }}</small>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="first_name" autocomplete="off" v-model="first_name"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.first_name">{{ errors.first_name[0] }}</small>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="last_name" autocomplete="title" v-model="last_name"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.last_name">{{ errors.last_name[0] }}</small>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="reg_year" class="block text-sm font-medium text-gray-700">Reg Year</label>
                            <input type="number" id="reg_year" autocomplete="off" v-model="reg_year"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.reg_year">{{ errors.reg_year[0] }}</small>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile
                                Number</label>
                            <input type="tel" id="mobile_number" autocomplete="title" v-model="mobile_number"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.mobile_number">{{ errors.mobile_number[0] }}</small>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="address" autocomplete="off" v-model="address"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.address">{{ errors.address[0] }}</small>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" autocomplete="off" v-model="email"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.email">{{ errors.email[0] }}</small>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" autocomplete="off" v-model="password"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.password">{{ errors.password[0] }}</small>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <label class="inline-flex items-center mt-3">
                                <input type="radio" class="form-radio h-5 w-5 text-indigo-600" name="gender" value="male" v-model="gender"><span class="ml-2 text-gray-700">Male</span>
                            </label>
                            <label class="inline-flex items-center mt-3">
                                <input type="radio" class="form-radio h-5 w-5 text-indigo-600" name="gender" value="female" v-model="gender"><span class="ml-2 text-gray-700">Female</span>
                            </label>
                            <br>
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.gender">{{ errors.gender[0] }}</small>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="create()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Create
                    </button>
                    <button type="button" @click="back()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";

export default {
    data() {
        return {
            opened: false,
            errors: {},
            username: '',
            first_name: '',
            last_name: '',
            reg_year: '',
            gender: '',
            password: '',
            address: '',
            email: '',
            mobile_number: '',
        }
    },
    mounted() {
        setTimeout(() => {
            this.opened = true
        }, 50)
    },
    methods: {
        back() {
            this.opened = false
            setTimeout(() => this.$router.back(), 300)
        },
        create() {
            let self = this;
            this.errors = {};
            let data = {
                username: this.username,
                first_name: this.first_name,
                last_name: this.last_name,
                reg_year: this.reg_year,
                gender: this.gender,
                password: this.password,
                address: this.address,
                email: this.email,
                mobile_number: this.mobile_number,
            }
            axios.post(`/api/students`, data).then(({data}) => {
                self.back();
                bus.$emit('item-created', data.item.id);
            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors
                }
            })
        }
    }
};
</script>
