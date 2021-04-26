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
                    <h3 class="font-bold text-lg text-gray-700">Edit Section "{{ section.section_no }}"</h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">

                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="section_no" class="block text-sm font-medium text-gray-700">Section NO</label>
                            <input type="number" id="section_no" autocomplete="title" v-model="section_no" disabled
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.section_no">{{ errors.section_no[0] }}</small>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="room_no" class="block text-sm font-medium text-gray-700">Room NO</label>
                            <input type="number" id="room_no" autocomplete="off" v-model="room_no"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.room_no">{{ errors.room_no[0] }}</small>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                            <input type="time" id="time" autocomplete="title" v-model="time" value="13:00"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.time">{{ errors.time[0] }}</small>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="instructor_id" class="block text-sm font-medium text-gray-700">Instructor</label>
                            <select id="instructor_id" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" v-model="instructor_id">
                                <option value="">[Select Instructor]</option>
                                <option :value="instructor.id" v-for="instructor in instructors">{{ instructor.full_name }}</option>
                            </select>
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.instructor_id">{{ errors.instructor_id[0] }}</small>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mb-2">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                            <select id="course_id" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" v-model="course_id">
                                <option value="">[Select Course]</option>
                                <option :value="course.id" v-for="course in courses">{{ course.title }}</option>
                            </select>
                            <small class="text-red-600 text-xs"
                                   v-if="errors && errors.course_id">{{ errors.course_id[0] }}</small>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="update()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update
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
            id: null,
            opened: false,
            errors: {},
            section: {},
            section_no: '',
            room_no: '',
            time: '',
            instructor_id: '',
            course_id: '',
            instructors: [],
            courses: [],
        }
    },
    mounted() {
        this.id = this.$route.params.id;
        axios.get('/api/instructors?per_page=50').then(({data})=>{
            this.instructors = data.entries;
            axios.get('/api/courses?per_page=50').then(({data})=>{
                this.courses = data.entries
            axios.get(`/api/sections/${this.id}`).then(({data}) => {
                this.section = data;
                this.section_no = this.section.section_no;
                this.room_no = this.section.room_no;
                this.time = this.section.time;
                this.instructor_id = this.section.instructor_id;
            })
            })
        });
        setTimeout(() => {
            this.opened = true;
        }, 50)
    },
    methods: {
        back() {
            this.opened = false
            setTimeout(() => this.$router.back(), 300)
        },
        update() {
            let self = this;
            this.errors = {};
            let data = {
                room_no: this.room_no,
                time: this.time,
                instructor_id: this.instructor_id,
                course_id: this.course_id,
            }
            axios.patch(`/api/sections/${this.id}`, data).then(({data}) => {
                self.back();
                bus.$emit('item-updated', data.item.id);
            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors
                }
            })
        }
    }
};
</script>
