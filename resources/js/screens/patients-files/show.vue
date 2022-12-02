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
        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg mt-8">
            <table class="bg-white min-w-full divide-y divide-gray-200 ">
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
                        <input type="date"
                               v-model="form.date"
                               :disabled="submitted"
                               class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

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
                        <span v-if="!payment.isEdit">
                            <b class="font-medium">{{ +payment.amount | numberFormat }}</b>
                        </span>
                        <input type="number"
                               v-model="payment.amount" v-else :disabled="submitted"
                               class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">

                    </td>
                    <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        <span v-if="!payment.isEdit">
                            <b class="font-medium" :class="{'text-red-500': payment.remaining_amount > 0}">{{
                                    +payment.remaining_amount | numberFormat
                                }}</b>
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
                        <a v-if="!payment.isEdit" href="#" @click="editPayment(payment)"
                           class="py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none">
                            <icon-edit
                                size="5"
                                class="transition-colors"
                            />
                        </a>
                        <a v-if="!payment.isEdit" href="#" @click="deletePayment(payment.id)"
                           class="py-1 inline-flex h-12 px-2 text-sm text-center text-red-600 transition-colors duration-200 transform lg:h-8 hover:text-red-700 focus:outline-none">
                            <icon-delete
                                size="5"
                                class="transition-colors"
                            />
                        </a>
                        <async-button v-if="payment.isEdit" @click="savePayment(payment)"
                                      :loading="submitted"
                                      class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none">
                            تعديل
                        </async-button>
                        <a v-if="payment.isEdit" href="#" @click="payment.isEdit = false"
                           class="ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none">
                            <span>إلغاء</span>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import AsyncButton from "../../components/AsyncButton";

export default {
    components: {
        AsyncButton
    },
    data() {
        return {
            id: null,
            payment_id: null,
            isFormManipulating: false,
            submitted: false,
            isEdit: false,
            patient_name: '',
            patient_file_number: '',
            data: [],
            type: '',
            totalPayments: 0,
            totalRemainingPayments: 0,
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
                this.data = data.map(item => {
                    item.isEdit = false;
                    return item;
                });
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
            axios.delete(`/api/patients-files/${id}`).then(({data}) => {
                bus.$emit('flash-message', {text: data.message, type: 'success'});
                this.getData();
            }).catch(({response}) => {
                bus.$emit('flash-message', {text: response.data.message, type: 'error'});
            }).finally(() => {
                this.submitted = false;
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
        }
    },
    computed: {
        isPatientFilesDetails: function () {
            return this.$route.name === 'patients-files-show'
        }
    }
};
</script>
