<template>
    <div :class="modalClasses" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div :class="overlayClasses" aria-hidden="true" @click="back"></div>

            <!-- Spacer for centering the modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div :class="modalPanelClasses">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="icon-container">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                            <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900">حذف {{ type }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">هل أنت متأكد من حذف هذا ال{{ type }}؟</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row">
                    <CAsyncButton
                        :loading="submitted"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mr-3 sm:w-auto sm:text-sm"
                        @click="deleteItem"
                    >
                        حذف
                    </CAsyncButton>
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mr-3 sm:w-auto sm:text-sm"
                        @click="back"
                    >
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRouter, useRoute } from "vue-router"
import axios from "axios"

const opened = ref(false)
const submitted = ref(false)
const id = ref(null)
const type = ref("")
const router = useRouter()
const route = useRoute()

const modalClasses = ref("fixed z-10 inset-0 overflow-y-auto")
const overlayClasses = ref("fixed inset-0 bg-gray-500 opacity-50 transition-opacity duration-200")
const modalPanelClasses = ref("inline-block w-full align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200")

const back = () => {
    opened.value = false
    setTimeout(() => router.back(), 100)
}

const deleteItem = () => {
    submitted.value = true
    axios
        .delete(`/api/patients/${id.value}`)
        .then(({ data }) => {
            // bus.emit("flash-message", { text: data.message, type: "success" });
            // bus.emit("item-deleted", id.value);
            back()
        })
        .catch(({ response }) => {
            // bus.emit("flash-message", { text: response.data.message, type: "error" });
        })
        .finally(() => {
            submitted.value = false
        })
}

onMounted(() => {
    id.value = route.params.id
    type.value = route.query.type
    setTimeout(() => {
        opened.value = true
    }, 50)
})
</script>

<style>
.icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 3rem;
    width: 3rem;
    background-color: #fee2e2; /* Tailwind: bg-red-100 */
    border-radius: 50%;
}
</style>
