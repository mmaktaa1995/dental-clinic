<template>
    <div :class="`fixed z-10 inset-0 overflow-y-auto `" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened ? 'scale-100' : 'scale-0'}`">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                            <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900">{{ title }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">{{ body }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row">
                    <CAsyncButton
                        type="button"
                        :loading="loading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="confirm"
                    >
                        تأكيد
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

<script setup lang="ts">
import { useRouter } from "vue-router"

const opened = defineModel<boolean>({ required: true })
const loading = defineModel<boolean>("loading", { required: true })

defineProps<{
    confirmTitle: string
    confirmBodyMessage: string
}>()

const $emits = defineEmits(["confirmCallback"])

const router = useRouter()
const back = () => {
    router.back()
}
const confirm = () => {
    $emits("confirmCallback")
}
</script>
