<template>
    <div
        :class="`fixed z-10 inset-0 overflow-y-auto `"
        aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <form @submit.prevent="update()"
              class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
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
                :class="`inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full duration-200  ${opened?'scale-100':'scale-0'}` ">

                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300 text-right">
                    <h3 class="text-lg text-gray-700 font-normal">تعديل بيانات الموعد <b class="font-bold"></b></h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="">
                            <label for="patient_id"
                                   class="block text-sm font-medium text-gray-700 text-right">المريض</label>
                            <select id="patient_id" autocomplete="off" v-model="form.patient_id"
                                    class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                                <option value="">اختر مريض</option>
                                <option :value="id" v-for="(name, id) in patients">{{ name }}</option>
                            </select>
                            <small class="text-red-600 text-xs text-right block"
                                   v-if="errors && errors.patient_id">{{ errors.patient_id[0] }}</small>
                        </div>
                        <div class="">
                            <label for="date" class="block text-sm font-medium text-gray-700 text-right">تاريخ
                                الزيارة</label>
                            <input type="date" id="date" autocomplete="off" v-model="form.date"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs text-right block"
                                   v-if="errors && errors.date">{{ errors.date[0] }}</small>
                        </div>

                        <div class="">
                            <label for="time" class="block text-sm font-medium text-gray-700 text-right">وقت
                                الزيارة</label>
                            <input type="time" id="time" autocomplete="off" v-model="form.time"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <small class="text-red-600 text-xs text-right block"
                                   v-if="errors && errors.time">{{ errors.time[0] }}</small>
                        </div>

                        <div class="col-span-full">
                            <label for="notes"
                                   class="block text-sm font-medium text-gray-700 text-right">الملاحظات</label>
                            <textarea id="notes" autocomplete="off" v-model="form.notes"
                                      class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full"></textarea>
                            <small class="text-red-600 text-xs text-right block"
                                   v-if="errors && errors.notes">{{ errors.notes[0] }}</small>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-revers">
                    <button type="button"
                            @click="back()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        إلغاء
                    </button>
                    <async-button
                        type="submit"
                        :loading="submitted"
                        class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm">
                        حفظ
                    </async-button>
                    <router-link
                        tag="a"
                        :to="{name: 'appointments-delete', params: {id}, query: {type:'موعد'}}"
                        class="mr-auto w-32 text-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        حذف
                    </router-link>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

import axios from "axios";

export default {
    data() {
        return {
            id: null,
            opened: false,
            submitted: false,
            errors: {},
            form: {
                date: '',
                time: '',
                notes: '',
                patient_id: '',
            },
            patients: []
        }
    },
    mounted() {
        this.id = this.$route.params.id;
        axios.get('/api/patients/dropdown').then(({data}) => {
            this.patients = data;
            axios.get(`/api/appointments/${this.id}`).then(({data}) => {
                let date = data.date.replace(/.(\d)*Z/g, '');
                this.form = {...data, date: date.split('T')[0], time: date.split('T')[1]};
            })
        })
        setTimeout(() => {
            this.opened = true;
        }, 50)
    },
    methods: {
        back() {
            this.opened = false;
            setTimeout(() => this.$router.back(), 300);
        },
        update() {
            let self = this;
            this.errors = {};
            this.submitted = true;
            axios.patch(`/api/appointments/${this.id}`, {...self.form}).then(({data}) => {
                bus.$emit('flash-message', {text: data.message, type: 'success'});
                bus.$emit('appointment-changed', 'true');
                self.back();
            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors
                }
            }).finally(() => {
                this.submitted = false;
            })
        }
    }
};
</script>
