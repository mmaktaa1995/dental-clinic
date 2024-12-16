<template>
    <div :class="`fixed z-10 inset-0 overflow-y-auto `" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened ? 'scale-100' : 'scale-0'}`">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300 text-right">
                    <h3 class="font-bold text-lg text-gray-700">إضافة مريض</h3>
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
                    <hr class="my-6" />
                    <div class="grid grid-cols-2 gap-6">
                        <div class="">
                            <label for="date" class="block text-sm font-medium text-gray-700 text-right">تاريخ الزيارة</label>
                            <input id="date" v-model="form.date" type="date" autocomplete="off" />
                            <small v-if="errors && errors.date" class="text-red-600 text-xs text-right block">{{ errors.date[0] }}</small>
                        </div>

                        <div class="">
                            <label for="amount" class="block text-sm font-medium text-gray-700 text-right">المبلغ المدفوع</label>
                            <input id="amount" v-model="form.amount" type="number" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors && errors.amount" class="text-red-600 text-xs text-right block">{{ errors.amount[0] }}</small>
                        </div>

                        <div class="">
                            <label for="remaining_amount" class="block text-sm font-medium text-gray-700 text-right">المبلغ المتبقي</label>
                            <input id="remaining_amount" v-model="form.remaining_amount" type="number" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors && errors.remaining_amount" class="text-red-600 text-xs text-right block">{{ errors.remaining_amount[0] }}</small>
                        </div>

                        <div class="col-span-full">
                            <label for="notes" class="block text-sm font-medium text-gray-700 text-right">الإجراء الذي تم</label>
                            <textarea id="notes" v-model="form.notes" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full"></textarea>
                            <small v-if="errors && errors.notes" class="text-red-600 text-xs text-right block">{{ errors.notes[0] }}</small>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-revers">
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="back"
                    >
                        إلغاء
                    </button>
                    <CAsyncButton
                        type="button"
                        :loading="submitted"
                        class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="create"
                        >تأكيد</CAsyncButton
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue"
import axios from "axios"
import moment from "moment"

const opened = ref(false)
const submitted = ref(false)
const errors = ref({})
const form = reactive({
    name: "",
    age: "",
    file_number: "",
    phone: "",
    date: "",
    amount: "",
    remaining_amount: "",
    notes: "",
    mobile: "",
})

const back = () => {
    opened.value = false
    setTimeout(() => {
        // Replace with actual routing logic if needed
        window.history.back()
    }, 300)
}

const create = () => {
    errors.value = {}
    submitted.value = true
    form.date = moment(form.date, "YYYY-MM-DD").add(1, "days")

    axios
        .post("/api/patients", { ...form })
        .then(({ data }) => {
            bus.$emit("flash-message", { text: data.message, type: "success" })
            bus.$emit("item-created", true)
            lastFileNumber++
            back()
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        })
        .finally(() => {
            submitted.value = false
        })
}

const resetForm = () => {
    form.name = ""
    form.age = ""
    form.file_number = ""
    form.phone = ""
    form.date = ""
    form.amount = ""
    form.remaining_amount = ""
    form.notes = ""
    form.mobile = ""
}

onMounted(() => {
    setTimeout(() => {
        opened.value = true
        resetForm()
        form.file_number = LAST_FILE_NUMBER // Replace with actual file number logic
    }, 50)
})
</script>
