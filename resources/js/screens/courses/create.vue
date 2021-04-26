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
                    <h3 class="font-bold text-lg text-gray-700">Add Course</h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">

                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" autocomplete="title" v-model="title"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs" v-if="errors && errors.title">{{errors.title[0]}}</small>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="hours" class="block text-sm font-medium text-gray-700">Hours</label>
                            <input type="number" id="hours" autocomplete="off" v-model="hours"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs" v-if="errors && errors.hours">{{errors.hours[0]}}</small>
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
            title: '',
            hours: '',
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
            axios.post(`/api/courses`, {title: self.title, hours: self.hours}).then(({data}) => {
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
