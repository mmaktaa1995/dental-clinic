<template>
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-16 py-8">
        <div class="col-span-full flex gap-5 mb-4">
            <div class="w-1/5">
                <select
                    v-model="year"
                    class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">>
                    <option value="">اختر السنة</option>
                    <option :value="y" v-for="y in years">{{ y }}</option>
                </select>
            </div>
            <div class="w-1/5">
                <select
                    v-model="month"
                    class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">>
                    <option value="">اختر الشهر</option>
                    <option :value="m" v-for="m in months">{{ m }}</option>
                </select>
            </div>
            <div class="w-1/5">
                <async-button type="button"
                              :loading="loading"
                              @click="getData()"
                              class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm">
                    بحث
                </async-button>
            </div>
        </div>
        <div class="col-span-full">
            <div class="flex flex-wrap">
                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">العدد الإجمالي للمرضى</h5>
                                    <span class="font-semibold text-xl text-blueGray-700 mt-3">{{ patientsTotalCount }}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                        <icon-users size="6" class="text-white"></icon-users>
                                    </div>
                                </div>
                            </div>
                          </div>
                    </div>
                </div>
                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">إجمالي الدخل</h5>
                                    <span class="font-semibold text-xl text-green-600 mt-3">{{ +incomeTotal | numberFormat}}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                        <icon-money size="6" class="text-white"></icon-money>
                                    </div>
                                </div>
                            </div>
                           </div>
                    </div>
                </div>
                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">إجمالي النفقات</h5>
                                    <span class="font-semibold text-xl text-red-600 mt-3">-{{ +expensesTotal | numberFormat}}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-pink-500">
                                        <icon-expenses size="6" class="text-white"></icon-expenses>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">صافي الربح الإجمالي</h5>
                                    <span class="font-semibold text-xl mt-3" :class="{'text-green-600': Math.sign(incomeTotal - expensesTotal) === 1,'text-red-600': Math.sign(incomeTotal - expensesTotal) === -1}">{{ (incomeTotal - expensesTotal) | numberFormat}}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-teal-500">
                                        <icon-money size="6" class="text-white"></icon-money>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-full flex gap-5 mb-4">
            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                <table class="bg-white min-w-full divide-y divide-gray-200 ">
                    <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                        <th colspan="2" class="py-2 px-3 text-right">هذه الإحصائيات تبعا للقيم المختارة أعلاه</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                            <b class="font-medium">عدد المرضى</b></td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                            {{ totalPatients }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                            <b class="font-medium">النفقات</b></td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                            {{ expensesSum | numberFormat }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                            <b class="font-medium">الواردات</b></td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                            {{ incomesSum | numberFormat }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                            <b class="font-medium">صافي الأرباح</b></td>
                        <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                            <span
                                :class="{'text-green-500': Math.sign(incomesSum - expensesSum) === 1,'text-red-500': Math.sign(incomesSum - expensesSum) === -1}">
                                {{ (incomesSum - expensesSum) | numberFormat }}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card" v-if="!loading && patients.length">
            <h1 class="text-lg font-semibold card-title">المرضى</h1>
            <div class="card-body">
                <pie-chart label="مرضى" :formatTooltipTitle="['المرضى']" v-if="patients.length"
                           :data="patients"></pie-chart>
            </div>
        </div>
        <div class="card" v-if="!loading && visits.length">
            <h1 class="text-lg font-semibold card-title">الزيارات</h1>
            <div class="card-body">
                <pie-chart label="زيارات" :formatTooltipTitle="['الزيارات']" v-if="visits.length"
                           :data="visits"></pie-chart>
            </div>
        </div>
        <div class="card col-span-full" v-if="!loading && expenses.length">
            <h1 class="text-lg font-semibold card-title">النفقات</h1>
            <div class="card-body">
                <bar-chart label="نفقات" color="red" :formatTooltipTitle="['النفقات']" v-if="expenses.length"
                           :data="expenses"></bar-chart>
            </div>
        </div>
        <div class="card col-span-full" v-if="!loading && incomes.length">
            <h1 class="text-lg font-semibold card-title">الواردات</h1>
            <div class="card-body">
                <bar-chart label="واردات" color="green" :formatTooltipTitle="['الواردات']" v-if="incomes.length"
                           :data="incomes"></bar-chart>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from "moment";
let year = new Date().getFullYear();
export default {
    data() {
        return {
            loading: false,
            year,
            month: '',
            patientsTotalCount: 0,
            expensesTotal: 0,
            totalPatients: 0,
            incomeTotal: 0,
            expensesSum: 0,
            incomesSum: 0,
            expenses: [],
            patients: [],
            visits: [],
            incomes: [],
            years: [],
            months: [
                1, 2, 3,
                4, 5, 6,
                7, 8, 9,
                10, 11, 12
            ]
        }
    },
    mounted() {
        this.years = this.range(2018, moment().year())
        this.getData();
    },
    methods: {
        range(start, stop, step = 1) {
            return Array.from({length: (stop - start) / step + 1}, (_, i) => start + (i * step))
        },
        getData() {
            this.loading = true;
            let query = [];
            let queryParams = '';
            if (this.year) {
                query.push('year=' + this.year);
            }
            if (this.month) {
                query.push('month=' + this.month);
            }
            queryParams = query.join('&')
            axios.get('/api/statistics?' + queryParams).then(({data}) => {
                this.expenses = data.expenses;
                this.visits = data.visits;
                this.incomes = data.incomes;
                this.patients = data.patients;

                this.totalPatients = this.patients.reduce((sum, item) => sum + +item.value, 0);
                this.expensesSum = this.expenses.reduce((sum, item) => sum + +item.value, 0);
                this.incomesSum = this.incomes.reduce((sum, item) => sum + +item.value, 0);

                this.patientsTotalCount = data.patientsTotalCount;
                this.expensesTotal = data.expensesTotal;
                this.incomeTotal = data.incomeTotal;
            }).finally(() => {
                this.loading = false;
            })
        }
    }
};
</script>
