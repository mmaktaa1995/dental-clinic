<template>
    <div :class="`fixed z-10 inset-0 overflow-y-auto`" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0" @submit.prevent="update">
            <!-- Background overlay -->
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200 ${opened ? 'scale-100' : 'scale-0'}`">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300 text-right">
                    <h3 class="text-lg text-gray-700 font-normal">تعديل بيانات الزيارة <b class="font-bold"></b></h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 text-right">الاسم</label>
                            <input id="name" v-model="form.name" type="text" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors.name" class="text-red-600 text-xs text-right block">{{ errors.name[0] }}</small>
                        </div>
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 text-right">تاريخ الزيارة</label>
                            <input id="date" v-model="form.date" type="date" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors.date" class="text-red-600 text-xs text-right block">{{ errors.date[0] }}</small>
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 text-right">المبلغ المدفوع</label>
                            <input id="amount" v-model="form.amount" type="number" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            <small v-if="errors.amount" class="text-red-600 text-xs text-right block">{{ errors.amount[0] }}</small>
                        </div>
                        <div class="col-span-full">
                            <label for="notes" class="block text-sm font-medium text-gray-700 text-right">الملاحظات</label>
                            <textarea id="notes" v-model="form.notes" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full"></textarea>
                            <small v-if="errors.notes" class="text-red-600 text-xs text-right block">{{ errors.notes[0] }}</small>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="back"
                    >
                        إلغاء
                    </button>
                    <CAsyncButton
                        type="submit"
                        :loading="submitted"
                        class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        حفظ
                    </CAsyncButton>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue"
import { useRouter, useRoute } from "vue-router"
import axios from "axios"
import { addDays, parseISO } from "date-fns"

const id = ref(null)
const opened = ref(false)
const submitted = ref(false)
const errors = reactive({})
const form = reactive({
    date: "",
    amount: "",
    description: "",
    name: "",
    notes: "",
})

const router = useRouter()
const route = useRoute()

const back = () => {
    opened.value = false
    setTimeout(() => router.back(), 300)
}

const update = async () => {
    errors.value = {}
    submitted.value = true

    try {
        form.date = addDays(parseISO(form.date), 1)
        const response = await axios.patch(`/api/expenses/${id.value}`, { ...form })
        bus.$emit("flash-message", { text: response.data.message, type: "success" })
        bus.$emit("item-updated", "true")
        back()
    } catch (error) {
        if (error.response && error.response.status === 422) {
            Object.assign(errors, error.response.data.errors)
        }
    } finally {
        submitted.value = false
    }
}

onMounted(async () => {
    id.value = route.params.id
    const { data } = await axios.get(`/api/expenses/${id.value}`)
    Object.assign(form, { ...data, amount: +data.amount })
    setTimeout(() => {
        opened.value = true
    }, 50)
})
</script>
