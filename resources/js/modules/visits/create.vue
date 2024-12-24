<template>
    <div :class="`fixed z-10 inset-0 overflow-y-auto`" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200 ${opened ? 'scale-100' : 'scale-0'}`">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300 text-right">
                    <h3 class="font-bold text-lg text-gray-700">إضافة زيارة</h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 text-right">المريض</label>
                            <select id="patient_id" v-model="form.patient_id" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                                <option value="">اختر مريض</option>
                                <option v-for="(name, id) in patients" :key="id" :value="id">{{ name }}</option>
                            </select>
                            <small v-if="errors.patient_id" class="text-pink-600 text-xs text-right block">{{ errors.patient_id[0] }}</small>
                        </div>
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 text-right">تاريخ الزيارة</label>
                            <input id="date" v-model="form.date" type="date" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors.date" class="text-pink-600 text-xs text-right block">{{ errors.date[0] }}</small>
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 text-right">المبلغ المدفوع</label>
                            <input id="amount" v-model="form.amount" type="number" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors.amount" class="text-pink-600 text-xs text-right block">{{ errors.amount[0] }}</small>
                        </div>
                        <div class="col-span-full">
                            <label for="notes" class="block text-sm font-medium text-gray-700 text-right">الملاحظات</label>
                            <textarea id="notes" v-model="form.notes" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full"></textarea>
                            <small v-if="errors.notes" class="text-pink-600 text-xs text-right block">{{ errors.notes[0] }}</small>
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
                        class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-cyan-700 text-base font-medium text-white hover:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="create"
                    >
                        تأكيد
                    </CAsyncButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue"
import axios from "axios"

const opened = ref(false)
const submitted = ref(false)
const errors = reactive({})
const form = reactive({
    date: "",
    amount: "",
    notes: "",
    patient_id: "",
})
const patients = ref([])

const back = () => {
    opened.value = false
    setTimeout(() => {
        history.back()
    }, 300)
}

const create = () => {
    submitted.value = true
    errors.value = {}
    axios
        .post("/api/visits", { ...form })
        .then(({ data }) => {
            bus.$emit("flash-message", { text: data.message, type: "success" })
            bus.$emit("item-created", true)
            back()
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                Object.assign(errors, error.response.data.errors)
            }
        })
        .finally(() => {
            submitted.value = false
        })
}

const resetForm = () => {
    form.date = ""
    form.amount = ""
    form.notes = ""
    form.patient_id = ""
}

onMounted(() => {
    setTimeout(() => {
        opened.value = true
        resetForm()
        axios.get("/api/patients/dropdown").then(({ data }) => {
            patients.value = data
        })
    }, 50)
})
</script>
