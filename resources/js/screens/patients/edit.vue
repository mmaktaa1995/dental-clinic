<template>
    <div :class="`fixed z-10 inset-0 overflow-y-auto `" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0" @submit.prevent="update()">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back()"></div>

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

            <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened ? 'scale-100' : 'scale-0'}`">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300 text-right">
                    <h3 class="text-lg text-gray-700 font-normal">
                        تعديل بيانات المريض <b class="font-bold">"{{ form.name }}"</b>
                    </h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="">
                            <label for="file_number" class="block text-sm font-medium text-gray-700 text-right">رقم الملف</label>
                            <input id="file_number" v-model="form.file_number" type="text" autocomplete="file_number" disabled class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors && errors.file_number" class="text-red-600 text-xs text-right block">{{ errors.file_number[0] }}</small>
                        </div>

                        <div class="">
                            <label for="name" class="block text-sm font-medium text-gray-700 text-right">الاسم الكامل</label>
                            <input id="name" v-model="form.name" type="text" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors && errors.name" class="text-red-600 text-xs text-right block">{{ errors.name[0] }}</small>
                        </div>

                        <div class="">
                            <label for="age" class="block text-sm font-medium text-gray-700 text-right">العمر</label>
                            <input id="age" v-model="form.age" type="number" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors && errors.age" class="text-red-600 text-xs text-right block">{{ errors.age[0] }}</small>
                        </div>

                        <div class="">
                            <label for="phone" class="block text-sm font-medium text-gray-700 text-right">رقم الهاتف</label>
                            <input id="phone" v-model="form.phone" type="tel" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors && errors.phone" class="text-red-600 text-xs text-right block">{{ errors.phone[0] }}</small>
                        </div>

                        <div class="">
                            <label for="mobile" class="block text-sm font-medium text-gray-700 text-right">رقم الموبايل</label>
                            <input id="mobile" v-model="form.mobile" type="tel" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors && errors.mobile" class="text-red-600 text-xs text-right block">{{ errors.mobile[0] }}</small>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-revers">
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="back()"
                    >
                        إلغاء
                    </button>
                    <async-button
                        type="submit"
                        :loading="submitted"
                        class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        حفظ
                    </async-button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios"

export default {
    data() {
        return {
            id: null,
            opened: false,
            errors: {},
            form: {
                name: "",
                age: "",
                file_number: "",
                phone: "",
                mobile: "",
            },
            submitted: false,
        }
    },
    mounted() {
        this.id = this.$route.params.id
        axios.get(`/api/patients/${this.id}`).then(({ data }) => {
            this.form = { ...data }
        })
        setTimeout(() => {
            this.opened = true
        }, 50)
    },
    methods: {
        back() {
            this.opened = false
            setTimeout(() => this.$router.back(), 300)
        },
        update() {
            const self = this
            this.errors = {}
            this.submitted = true
            axios
                .patch(`/api/patients/${this.id}`, { ...self.form })
                .then(({ data }) => {
                    bus.$emit("flash-message", { text: data.message, type: "success" })
                    bus.$emit("item-updated", "true")
                    self.back()
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors
                    }
                })
                .finally(() => {
                    this.submitted = false
                })
        },
    },
}
</script>
