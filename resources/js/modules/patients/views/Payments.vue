<template>
    <c-container>
        <div class="w-full text-left">
            <button class="py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-purple-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-purple-600 focus:outline-none" @click="print()">طباعة</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="">
                <label for="name" class="block text-sm font-medium text-gray-700 text-right">الاسم</label>
                <input id="name" v-model="patient_name" type="text" disabled class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
            </div>
            <div class="">
                <label for="file_number" class="block text-sm font-medium text-gray-700 text-right">رقم الملف</label>
                <input id="file_number" v-model="patient_file_number" type="text" disabled class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
            </div>
            <div class="">
                <label for="totalPayments" class="block text-sm font-medium text-gray-700 text-right">إجمالي المبلغ المدفوع</label>
                <input id="totalPayments" v-model="totalPayments" type="text" disabled class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
            </div>
            <div class="">
                <label for="totalPayments" class="block text-sm font-medium text-gray-700 text-right">إجمالي المبلغ المتبقي</label>
                <input id="totalRemainingPayments" v-model="totalRemainingPayments" type="text" disabled :class="{ 'text-red-600': totalRemainingPayments > 0 }" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
            </div>
        </div>

        <div class="sm:block mt-4">
            <nav class="flex">
                <a href="#" :class="'px-3 py-2 font-medium text-sm leading-5 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:text-indigo-600 focus:bg-indigo-200 ' + (currentTab === 'PAYMENTS' ? 'text-indigo-700 bg-indigo-200' : '')" @click.prevent="(currentTab = 'PAYMENTS')">
                    الدفعات
                </a>
                <a
                    href="#"
                    :class="'ml-4 px-3 py-2 font-medium text-sm leading-5 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:text-indigo-600 focus:bg-indigo-200 ' + (currentTab === 'DELETED_PAYMENTS' ? 'text-indigo-700 bg-indigo-200' : '')"
                    @click.prevent="(currentTab = 'DELETED_PAYMENTS')"
                >
                    الدفعات المحذوفة
                </a>
            </nav>
        </div>

        <div>
            <TransitionGroup name="list" tag="div" class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg mt-8">
                <table v-if="currentTab === 'PAYMENTS'" key="1" class="bg-white min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                            <th colspan="4" class="py-2 px-3 text-right">الدفعات</th>
                            <th class="py-2 px-3 text-left">
                                <a href="#" class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none" @click="add"> إضافة </a>
                            </th>
                        </tr>
                        <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                            <th class="py-2 px-3 text-right">الإجراء الذي تم</th>
                            <th class="py-2 px-3 text-right">المبلغ المدفوع</th>
                            <th class="py-2 px-3 text-right">المبلغ المتبقي</th>
                            <th class="py-2 px-3 text-right">التاريخ</th>
                            <th class="py-2 px-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-if="isFormManipulating">
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <input v-model="form.notes" type="text" :disabled="submitted" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                                <input v-model="form.amount" type="number" :disabled="submitted" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                                <input v-model="form.remaining_amount" type="number" :disabled="submitted" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <input v-model="form.date" type="date" :disabled="submitted" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <CAsyncButton :loading="submitted" class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none" @click="addPayment()">
                                    حفظ
                                </CAsyncButton>
                                <a href="#" class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none" @click="cancel"> إلغاء </a>
                            </td>
                        </tr>
                        <tr v-for="payment in data" :key="payment.id">
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <span v-if="!payment.isEdit">
                                    {{ payment.visit.notes ? payment.visit.notes : "-" }}
                                </span>
                                <input v-else v-model="payment.visit.notes" type="text" :disabled="submitted" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                                <span v-if="!payment.isEdit && !payment.isPayDebtOpened">
                                    <b class="font-medium">{{ +payment.amount }}</b>
                                </span>
                                <input v-else v-model="payment.amount" type="number" :disabled="submitted" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                                <span v-if="!payment.isEdit">
                                    <b v-if="!payment.isPayDebtOpened" class="font-medium" :class="{ 'text-red-500': payment.remaining_amount > 0 }">
                                        {{ +payment.remaining_amount }}
                                    </b>
                                    <b v-else class="font-medium" :class="{ 'text-red-500': payment.remaining_amount > 0 }">
                                        {{ +(payment.remaining_amount - payment.amount) }}
                                    </b>
                                </span>
                                <input v-else v-model="payment.remaining_amount" type="number" :disabled="submitted" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <span v-if="!payment.isEdit">
                                    {{ payment.date }}
                                </span>
                                <input v-else v-model="payment.date" type="date" :disabled="submitted" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <a v-if="!payment.isEdit && !payment.isPayDebtOpened" href="#" class="py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none" @click="editPayment(payment)">
                                    <c-icon-edit size="5" class="transition-colors" />
                                </a>
                                <a v-if="!payment.isEdit && !payment.isPayDebtOpened" href="#" class="py-1 inline-flex h-12 px-2 text-sm text-center text-red-600 transition-colors duration-200 transform lg:h-8 hover:text-red-700 focus:outline-none" @click="deletePayment(payment.id)">
                                    <c-icon-delete size="5" class="transition-colors" />
                                </a>
                                <a
                                    v-if="payment.remaining_amount > 0 && !payment.isPayDebtOpened && !payment.isEdit"
                                    href="#"
                                    class="py-1 inline-flex h-12 px-2 text-sm text-center text-teal-600 transition-colors duration-200 transform lg:h-8 hover:text-teal-700 focus:outline-none"
                                    @click="addPaymentForDebt(payment)"
                                >
                                    >
                                    <c-icon-money size="5" class="transition-colors" />
                                </a>
                                <CAsyncButton
                                    v-if="payment.isEdit || payment.isPayDebtOpened"
                                    :loading="submitted"
                                    class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none"
                                    @click="savePayment(payment)"
                                >
                                    {{ payment.isPayDebtOpened ? "حفظ" : "تعديل" }}
                                </CAsyncButton>
                                <a
                                    v-if="payment.isEdit || payment.isPayDebtOpened"
                                    href="#"
                                    class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none"
                                    @click="cancelPayment(payment)"
                                >
                                    <span>إلغاء</span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table v-if="currentTab === 'DELETED_PAYMENTS'" key="2" class="bg-white min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                            <th colspan="5" class="py-2 px-3 text-right">الدفعات المحذوفة</th>
                        </tr>
                        <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                            <th class="py-2 px-3 text-right">الإجراء الذي تم</th>
                            <th class="py-2 px-3 text-right">المبلغ المدفوع</th>
                            <th class="py-2 px-3 text-right">المبلغ المتبقي</th>
                            <th class="py-2 px-3 text-right">التاريخ</th>
                            <th class="py-2 px-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="payment in deletedPayments" :key="payment.id">
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <span>
                                    {{ payment.visit.notes ? payment.visit.notes : "-" }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                                <span>
                                    <b class="font-medium">{{ +payment.amount }}</b>
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                                <span v-if="!payment.isEdit">
                                    <b class="font-medium" :class="{ 'text-red-500': payment.remaining_amount > 0 }">
                                        {{ +payment.remaining_amount }}
                                    </b>
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <span>
                                    {{ payment.date }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                                <a href="#" title="استعادة" class="py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none" @click="restorePayment(payment)">
                                    <c-icon-restore size="5" class="transition-colors" />
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </TransitionGroup>
        </div>
        <div v-if="opened" :class="`fixed z-10 inset-0 overflow-y-auto `" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="(opened = false)"></div>

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
                <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened ? 'scale-100' : 'scale-0'}`">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                                <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900">حذف دفعة</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">هل أنت متأكد من حذف هذه الدفعة؟</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row">
                        <CAsyncButton
                            type="button"
                            :loading="submitted"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mr-3 sm:w-auto sm:text-sm"
                            @click="deletePayment(payment_id)"
                        >
                            حذف
                        </CAsyncButton>
                        <button
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mr-3 sm:w-auto sm:text-sm"
                            @click="(opened = false)"
                        >
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </c-container>
</template>

<script setup>
import { usePatientDetailStore } from "@/modules/patients/detailStore.ts"

const patientDetailsStore = usePatientDetailStore()
</script>
