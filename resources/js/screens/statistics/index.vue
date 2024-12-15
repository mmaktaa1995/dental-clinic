<template>
    <div v-if="!passwordCorrect">
        <div class="max-w-md mx-auto py-4">
            <label for="password-statistics" class="block text-sm font-medium text-gray-700 text-right">كلمة المرور</label>
            <input
                id="password-statistics"
                ref="passwordStatistics"
                v-model="password"
                type="password"
                autocomplete="off"
                autofocus="autofocus"
                class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full"
                @input="checkPassword"
            />
        </div>
    </div>
    <div v-else class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-16 py-8">
        <div class="col-span-full flex gap-5 mb-4">
            <div class="w-1/5">
                <select v-model="year" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                    >
                    <option value="">اختر السنة</option>
                    <option v-for="y in years" :value="y">{{ y }}</option>
                </select>
            </div>
            <div class="w-1/5">
                <select v-model="month" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" @change="monthChanged()">
                    >
                    <option value="">اختر الشهر</option>
                    <option v-for="m in months" :value="m">{{ m }}</option>
                </select>
            </div>
            <div v-if="month" class="w-1/5">
                <select v-model="day" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                    >
                    <option value="">اختر اليوم</option>
                    <option v-for="d in days" :value="d">{{ d }}</option>
                </select>
            </div>
            <div class="w-1/5">
                <async-button
                    type="button"
                    :loading="loading"
                    class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
                    @click="getData()"
                >
                    بحث
                </async-button>
            </div>
        </div>
        <div class="col-span-full">
            <div class="flex flex-wrap">
                <div class="w-full lg:w-6/12 xl:w-3/12 pl-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">العدد الإجمالي للمرضى</h5>
                                    <span class="font-semibold text-xl text-blueGray-700 mt-3">{{ patientsTotalCount }}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                        <c-icon-users size="6" class="text-white"></c-icon-users>
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
                                    <span class="font-semibold text-xl text-green-600 mt-3">{{ +incomeTotal | numberFormat }}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                        <c-icon-money size="6" class="text-white"></c-icon-money>
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
                                    <span class="font-semibold text-xl text-red-600 mt-3">-{{ +expensesTotal | numberFormat }}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-pink-500">
                                        <c-icon-expenses size="6" class="text-white"></c-icon-expenses>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-6/12 xl:w-3/12 pr-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">صافي الربح الإجمالي</h5>
                                    <span class="font-semibold text-xl mt-3" :class="{ 'text-green-600': Math.sign(incomeTotal - expensesTotal) === 1, 'text-red-600': Math.sign(incomeTotal - expensesTotal) === -1 }">{{ (incomeTotal - expensesTotal) | numberFormat }}</span>
                                </div>
                                <div class="relative w-auto pl-4 flex-initial">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-teal-500">
                                        <c-icon-money size="6" class="text-white"></c-icon-money>
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
                <table class="bg-white min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                            <th colspan="2" class="py-2 px-3 text-right">هذه الإحصائيات تبعا للقيم المختارة أعلاه</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium">عدد المرضى</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                                {{ totalPatients }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium"> المبالغ المتبقية لدى المرضى</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-red-500">
                                {{ +totalDebts | numberFormat }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium">النفقات</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                                {{ expensesSum | numberFormat }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium">الواردات</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                                {{ incomesSum | numberFormat }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium">صافي الأرباح</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                                <span :class="{ 'text-green-500': Math.sign(incomesSum - expensesSum) === 1, 'text-red-500': Math.sign(incomesSum - expensesSum) === -1 }"> {{ (incomesSum - expensesSum) | numberFormat }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="!loading && patients.length" class="card">
            <h1 class="text-lg font-semibold card-title">المرضى</h1>
            <div class="card-body">
                <apex-polar-chart v-if="patients.length" label="مريض" :format-tooltip-title="['المرضى']" :data="patients"></apex-polar-chart>
            </div>
        </div>
        <div v-if="!loading && visits.length" class="card">
            <h1 class="text-lg font-semibold card-title">الزيارات</h1>
            <div class="card-body">
                <apex-polar-chart v-if="visits.length" label="زيارة" :format-tooltip-title="['الزيارات']" :data="visits"></apex-polar-chart>
            </div>
        </div>
        <div v-if="!loading && incomes.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">الواردات</h1>
            <div class="card-body">
                <apex-line-chart v-if="incomes.length" label="<b class='mr-1'>واردات</b>" color="green" :format-tooltip-title="['الواردات']" :suggested-max="suggestedMax(incomes)" :data="incomes"></apex-line-chart>
            </div>
        </div>
        <div v-if="!loading && expenses.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">النفقات</h1>
            <div class="card-body">
                <apex-line-chart v-if="expenses.length" label="<b class='mr-1'>نفقات</b>" color="red" :format-tooltip-title="['النفقات']" :suggested-max="suggestedMax(expenses)" :data="expenses"></apex-line-chart>
            </div>
        </div>
        <div v-if="!loading && incomes.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">الواردات و النفقات</h1>
            <div class="card-body">
                <apex-line-chart
                    v-if="incomes.length"
                    label="واردات"
                    :colors="['blue', 'red']"
                    :format-tooltip-title="['الواردات']"
                    :suggested-max="suggestedMax(incomes)"
                    :data="incomes"
                    :series="[
                        { name: '<b class=\'mr-1\'>واردات </b>', data: incomes.map((_) => _.value) },
                        { name: '<b class=\'mr-1\'>نفقات </b>', data: expenses.map((_) => _.value) },
                    ]"
                ></apex-line-chart>
            </div>
        </div>
        <div v-if="!loading && debts.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">المبالغ المتبقية</h1>
            <div class="card-body">
                <apex-line-chart v-if="debts.length" label="<b class='mr-1'>ديون</b>" :format-tooltip-title="['المبالغ المتبقية']" :suggested-max="suggestedMax(debts)" color="default" :data="debts"></apex-line-chart>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
import moment from "moment"
import InteractsWithMetrics from "./../../mixins/interactsWithMetrics"

const year = new Date().getFullYear()
export default {
    mixins: [InteractsWithMetrics],
    data() {
        return {
            loading: false,
            passwordCorrect: false,
            year,
            month: "",
            password: "",
            day: "",
            patientsTotalCount: 0,
            expensesTotal: 0,
            totalPatients: 0,
            incomeTotal: 0,
            expensesSum: 0,
            totalDebts: 0,
            incomesSum: 0,
            expenses: [],
            debts: [],
            patients: [],
            visits: [],
            incomes: [],
            days: [],
            years: [],
            months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        }
    },
    mounted() {
        this.years = this.range(2018, moment().year())
        this.getData()
        this.$refs.passwordStatistics.focus()
        setInterval(() => {
            this.passwordCorrect = false
            this.password = ""
        }, 120000)
    },
    methods: {
        monthChanged() {
            const year = this.year ? this.year : "2022"
            this.days = this.range(1, moment(`${year}-${this.month}`).daysInMonth())
        },
        range(start, stop, step = 1) {
            return Array.from({ length: (stop - start) / step + 1 }, (_, i) => start + i * step)
        },
        getData() {
            this.loading = true
            const query = []
            let queryParams = ""
            if (this.year) {
                query.push("year=" + this.year)
            }
            if (this.month) {
                query.push("month=" + this.month)
            }
            if (this.day && this.month) {
                query.push("day=" + this.day)
            }
            queryParams = query.join("&")
            axios
                .get("/api/statistics?" + queryParams)
                .then(({ data }) => {
                    this.expenses = data.expenses
                    this.visits = data.visits
                    this.incomes = data.incomes
                    this.patients = data.patients
                    this.debts = data.debts

                    this.totalPatients = this.patients.reduce((sum, item) => sum + +item.value, 0)
                    this.expensesSum = this.expenses.reduce((sum, item) => sum + +item.value, 0)
                    this.incomesSum = this.incomes.reduce((sum, item) => sum + +item.value, 0)

                    this.patientsTotalCount = data.patientsTotalCount
                    this.expensesTotal = data.expensesTotal
                    this.incomeTotal = data.incomeTotal
                    this.totalDebts = data.totalDebts
                })
                .finally(() => {
                    this.loading = false
                })
        },
        checkPassword() {
            this.passwordCorrect = this.password === "1256"
        },
    },
}
</script>
