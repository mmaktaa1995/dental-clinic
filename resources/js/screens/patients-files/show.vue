<template>
    <div class="px-12 py-6">
        <div class="w-full text-left">
            <button @click="print()"
                    class="py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-purple-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-purple-600 focus:outline-none">
                طباعة
            </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="">
                <label for="name" class="block text-sm font-medium text-gray-700 text-right">الاسم</label>
                <input type="text" id="name" disabled
                       v-model="patient_name"
                       class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
            </div>
            <div class="">
                <label for="file_number" class="block text-sm font-medium text-gray-700 text-right">رقم الملف</label>
                <input type="text" id="file_number" disabled
                       v-model="patient_file_number"
                       class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
            </div>
            <div class="">
                <label for="totalPayments" class="block text-sm font-medium text-gray-700 text-right">إجمالي المبلغ
                    المدفوع</label>
                <input type="text" id="totalPayments" disabled
                       v-model="totalPayments"
                       class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
            </div>
            <div class="">
                <label for="totalPayments" class="block text-sm font-medium text-gray-700 text-right">إجمالي المبلغ
                    المتبقي</label>
                <input type="text" id="totalRemainingPayments" disabled
                       v-model="totalRemainingPayments"
                       :class="{'text-red-600' : totalRemainingPayments > 0}"
                       class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
            </div>
        </div>

        <div class="sm:block mt-4">
            <nav class="flex">
                <a
                    href="#"
                    @click.prevent="currentTab = 'PAYMENTS'"
                    :class="
                                'px-3 py-2 font-medium text-sm leading-5 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:text-indigo-600 focus:bg-indigo-200 ' +
                                (currentTab === 'PAYMENTS' ? 'text-indigo-700 bg-indigo-200' : '')
                            "
                >
                    الدفعات
                </a>
                <a
                    href="#"
                    @click.prevent="currentTab = 'DELETED_PAYMENTS'"
                    :class="
                                'ml-4 px-3 py-2 font-medium text-sm leading-5 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:text-indigo-600 focus:bg-indigo-200 ' +
                                (currentTab === 'DELETED_PAYMENTS' ? 'text-indigo-700 bg-indigo-200' : '')
                            "
                >
                    الدفعات المحذوفة
                </a>
            </nav>
        </div>

        <div >
            <TransitionGroup name="list" tag="div" class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg mt-8">
                <table class="bg-white min-w-full divide-y divide-gray-200" key="1" v-if="currentTab === 'PAYMENTS'">
                    <thead>
                    <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                        <th colspan="4" class="py-2 px-3 text-right">الدفعات</th>
                        <th class="py-2 px-3 text-left">
                            <a href="#" @click="isFormManipulating = true;isEdit = false"
                               class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none">
                                إضافة
                            </a>
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
                            <input type="text"
                                   v-model="form.notes"
                                   :disabled="submitted"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                            <input type="number"
                                   v-model="form.amount"
                                   :disabled="submitted"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                            <input type="number"
                                   v-model="form.remaining_amount"
                                   :disabled="submitted"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                            <date-picker type="date"
                                         v-model="form.date"
                                         :disabled="submitted"
                            ></date-picker>

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                            <async-button @click="addPayment()"
                                          :loading="submitted"
                                          class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none">
                                حفظ
                            </async-button>
                            <a href="#" @click="resetForm();isFormManipulating = false;"
                               class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none">
                                إلغاء
                            </a>
                        </td>
                    </tr>
                    <tr v-for="payment in data">
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                        <span v-if="!payment.isEdit">
                            {{ payment.visit.notes ? payment.visit.notes : '-' }}
                        </span>
                            <input type="text"
                                   v-model="payment.visit.notes" v-else :disabled="submitted"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        <span v-if="!payment.isEdit && !payment.isPayDebtOpened">
                            <b class="font-medium">{{ +payment.amount | numberFormat }}</b>
                        </span>
                            <input type="number"
                                   v-model="payment.amount" v-else :disabled="submitted"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        <span v-if="!payment.isEdit">
                            <b v-if="!payment.isPayDebtOpened" class="font-medium"
                               :class="{'text-red-500': payment.remaining_amount > 0}">
                                {{ +payment.remaining_amount | numberFormat }}
                            </b>
                            <b v-else class="font-medium" :class="{'text-red-500': payment.remaining_amount > 0}">
                                {{ +(payment.remaining_amount - payment.amount) | numberFormat }}
                            </b>
                        </span>
                            <input type="number"
                                   v-model="payment.remaining_amount" v-else :disabled="submitted"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                        <span v-if="!payment.isEdit">
                            {{ payment.date }}
                        </span>
                            <input type="date"
                                   v-model="payment.date" v-else :disabled="submitted"
                                   class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                            <a v-if="!payment.isEdit && !payment.isPayDebtOpened" href="#" @click="editPayment(payment)"
                               class="py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none">
                                <icon-edit
                                    size="5"
                                    class="transition-colors"
                                />
                            </a>
                            <a v-if="!payment.isEdit && !payment.isPayDebtOpened" href="#"
                               @click="deletePayment(payment.id)"
                               class="py-1 inline-flex h-12 px-2 text-sm text-center text-red-600 transition-colors duration-200 transform lg:h-8 hover:text-red-700 focus:outline-none">
                                <icon-delete
                                    size="5"
                                    class="transition-colors"
                                />
                            </a>
                            <a v-if="payment.remaining_amount > 0 && !payment.isPayDebtOpened && !payment.isEdit"
                               href="#"
                               @click="addPaymentForDebt(payment)"
                               class="py-1 inline-flex h-12 px-2 text-sm text-center text-teal-600 transition-colors duration-200 transform lg:h-8 hover:text-teal-700 focus:outline-none">
                                <icon-money
                                    size="5"
                                    class="transition-colors"
                                />
                            </a>
                            <async-button v-if="payment.isEdit || payment.isPayDebtOpened" @click="savePayment(payment)"
                                          :loading="submitted"
                                          class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none">
                                {{ payment.isPayDebtOpened ? 'حفظ' : 'تعديل' }}
                            </async-button>
                            <a v-if="payment.isEdit || payment.isPayDebtOpened" href="#" @click="cancelPayment(payment)"
                               class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none">
                                <span>إلغاء</span>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="bg-white min-w-full divide-y divide-gray-200" key="2" v-if="currentTab === 'DELETED_PAYMENTS'">
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
                    <tr v-for="payment in deletedPayments">
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                        <span>
                            {{ payment.visit.notes ? payment.visit.notes : '-' }}
                        </span>
                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        <span>
                            <b class="font-medium">{{ +payment.amount | numberFormat }}</b>
                        </span>
                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        <span v-if="!payment.isEdit">
                            <b class="font-medium"
                               :class="{'text-red-500': payment.remaining_amount > 0}">
                                {{ +payment.remaining_amount | numberFormat }}
                            </b>
                        </span>
                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                        <span>
                            {{ payment.date }}
                        </span>

                        </td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500">
                            <a href="#" @click="restorePayment(payment)"
                               title="استعادة"
                               class="py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none">
                                <icon-restore
                                    size="5"
                                    class="transition-colors"
                                />
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </TransitionGroup>
        </div>
        <div
            v-if="opened"
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
                    @click="opened= false"
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
                    :class="`inline-block w-full align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened?'scale-100':'scale-0'}` ">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    حذف دفعة
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        هل أنت متأكد من حذف هذه الدفعة؟
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row">
                        <async-button
                            type="button"
                            :loading="submitted"
                            @click="deletePayment(payment_id)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mr-3 sm:w-auto sm:text-sm"
                        >
                            حذف
                        </async-button>
                        <button type="button" @click="opened= false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mr-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import AsyncButton from "../../components/AsyncButton";
import DatePicker from "vue2-datepicker";
import moment from "moment";

export default {
    components: {
        AsyncButton,
        DatePicker
    },
    data() {
        return {
            id: null,
            payment_id: null,
            isFormManipulating: false,
            submitted: false,
            isEdit: false,
            opened: false,
            isDeletedTab: false,
            patient_name: '',
            currentTab: 'PAYMENTS',
            patient_file_number: '',
            data: [],
            deletedPayments: [],
            type: '',
            totalPayments: 0,
            totalRemainingPayments: 0,
            currentPayment: {},
            form: {
                amount: '',
                remaining_amount: '',
                date: '',
                notes: ''
            }
        }
    },
    mounted() {
        this.id = this.$route.params.id;
        this.type = this.$route.query.type;
        this.getData()
        let self = this;
        bus.$on('item-deleted', function () {
            self.getData();
        })
    },
    methods: {
        back() {
            setTimeout(() => this.$router.back(), 100)
        },
        resetForm() {
            this.form = {
                amount: '',
                remaining_amount: '',
                date: '',
                notes: ''
            };
        },
        getData() {
            axios.get(`/api/patients-files/${this.id}`).then(({data}) => {
                this.data = data.payments.map(item => {
                    item.isEdit = false;
                    item.isPayDebtOpened = false;
                    return item;
                });
                this.deletedPayments = data.deleted_payments;
                if (this.data.length) {
                    this.patient_name = this.data[0].patient.name;
                    this.patient_file_number = this.data[0].patient.file_number;
                    this.totalPayments = this.data.reduce((sum, payment) => sum + +payment.amount, 0)
                    this.totalRemainingPayments = this.data.reduce((sum, payment) => sum + +payment.remaining_amount, 0)
                }
            })
        },
        print() {
            axios.get(`/api/patients-files/${this.id}/print`).then(({data}) => {
                window.open(data.url, 'blank');
                console.log(data)
            })
        },
        addPayment() {
            this.submitted = true;
            let data = {...this.form, patient_id: this.id}
            data.date = moment(data.date, 'YYYY-MM-DD').add(1, 'days')
            axios.post(`/api/patients-files`, data).then(({data}) => {
                bus.$emit('flash-message', {text: data.message, type: 'success'});
                this.resetForm();
                this.isFormManipulating = false;
                this.getData();
            }).catch(({response}) => {
                bus.$emit('flash-message', {text: response.data.message, type: 'error'});
            }).finally(() => {
                this.submitted = false;
            })
        },
        savePayment(payment) {
            this.submitted = true;
            let data = {
                amount: payment.amount,
                remaining_amount: payment.remaining_amount,
                date: payment.date,
                notes: payment.visit.notes,
                patient_id: this.id
            }
            if (payment.isPayDebtOpened) {
                data.is_pay_debt = true;
                data.old_amount = this.currentPayment.amount;
            }
            axios.put(`/api/patients-files/${this.payment_id}`, data).then(({data}) => {
                bus.$emit('flash-message', {text: data.message, type: 'success'});
                this.resetForm();
                payment.isEdit = false;
                this.getData();
            }).catch(({response}) => {
                bus.$emit('flash-message', {text: response.data.message, type: 'error'});
            }).finally(() => {
                this.submitted = false;
            })
        },
        deletePayment(id) {
            this.payment_id = id;
            if (!this.opened) {
                this.opened = true;
                return;
            }
            axios.delete(`/api/patients-files/${id}`).then(({data}) => {
                bus.$emit('flash-message', {text: data.message, type: 'success'});
                this.getData();
            }).catch(({response}) => {
                bus.$emit('flash-message', {text: response.data.message, type: 'error'});
            }).finally(() => {
                this.submitted = false;
                this.opened = false;
            })
        },
        restorePayment(payment) {
            axios.post(`/api/patients-files/${payment.id}/restore`).then(({data}) => {
                bus.$emit('flash-message', {text: data.message, type: 'success'});
                this.getData();
            }).catch(({response}) => {
                bus.$emit('flash-message', {text: response.data.message, type: 'error'});
            }).finally(() => {
                this.submitted = false;
                this.opened = false;
            })
        },
        editPayment(payment) {
            payment.isEdit = true;
            this.payment_id = payment.id;
            this.form = {
                amount: payment.amount,
                remaining_amount: payment.remaining_amount,
                date: payment.date,
                notes: payment.visit.notes
            };
        },
        addPaymentForDebt(payment) {
            this.currentPayment = {...payment};
            payment.isPayDebtOpened = true;
            this.payment_id = payment.id;
            payment.amount = 0;
        },
        cancelPayment(payment) {
            payment.isEdit = false;
            payment.isPayDebtOpened = false;
            payment.amount = this.currentPayment.amount
        }
    },
    computed: {
        isPatientFilesDetails: function () {
            return this.$route.name === 'patients-files-show'
        }
    }
};
</script>
