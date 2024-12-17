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
                    <option value="">اختر السنة</option>
                    <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                </select>
            </div>
            <div class="w-1/5">
                <select v-model="month" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" @change="monthChanged()">
                    <option value="">اختر الشهر</option>
                    <option v-for="m in months" :key="m" :value="m">{{ m }}</option>
                </select>
            </div>
            <div v-if="month" class="w-1/5">
                <select v-model="day" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                    <option value="">اختر اليوم</option>
                    <option v-for="d in days" :key="d" :value="d">{{ d }}</option>
                </select>
            </div>
            <div class="w-1/5">
                <CAsyncButton
                    type="primary"
                    :loading="loading"
                    class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
                    @click="getData"
                >
                    بحث
                </CAsyncButton>
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
                                    <span class="font-semibold text-xl text-green-600 mt-3">{{ +incomeTotal }}</span>
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
                                    <span class="font-semibold text-xl text-red-600 mt-3">-{{ +expensesTotal }}</span>
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
                                    <span class="font-semibold text-xl mt-3" :class="{ 'text-green-600': Math.sign(incomeTotal - expensesTotal) === 1, 'text-red-600': Math.sign(incomeTotal - expensesTotal) === -1 }">{{ incomeTotal - expensesTotal }}</span>
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
                                {{ +totalDebts }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium">النفقات</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                                {{ expensesSum }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium">الواردات</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                                {{ incomesSum }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700">
                                <b class="font-medium">صافي الأرباح</b>
                            </td>
                            <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500">
                                <span :class="{ 'text-green-500': Math.sign(incomesSum - expensesSum) === 1, 'text-red-500': Math.sign(incomesSum - expensesSum) === -1 }"> {{ incomesSum - expensesSum }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="!loading && patients.length" class="card">
            <h1 class="text-lg font-semibold card-title">المرضى</h1>
            <div class="card-body">
                <c-apex-polar-chart v-if="patients.length" label="مريض" :format-tooltip-title="['المرضى']" :data="patients"></c-apex-polar-chart>
            </div>
        </div>
        <div v-if="!loading && visits.length" class="card">
            <h1 class="text-lg font-semibold card-title">الزيارات</h1>
            <div class="card-body">
                <c-apex-polar-chart v-if="visits.length" label="زيارة" :format-tooltip-title="['الزيارات']" :data="visits"></c-apex-polar-chart>
            </div>
        </div>
        <div v-if="!loading && incomes.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">إحصائيات الواردات</h1>
            <div class="card-body">
                <c-apex-polar-chart v-if="incomes.length" label="إيراد" :format-tooltip-title="['الواردات']" :data="incomes"></c-apex-polar-chart>
            </div>
        </div>
        <div v-if="!loading && incomes.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">الواردات</h1>
            <div class="card-body">
                <c-apex-line-chart v-if="incomes.length" label="<b class='mr-1'>واردات</b>" color="green" :format-tooltip-title="['الواردات']" :suggested-max="suggestedMax(incomes)" :data="incomes"></c-apex-line-chart>
            </div>
        </div>
        <div v-if="!loading && expenses.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">النفقات</h1>
            <div class="card-body">
                <c-apex-line-chart v-if="expenses.length" label="<b class='mr-1'>نفقات</b>" color="red" :format-tooltip-title="['النفقات']" :suggested-max="suggestedMax(expenses)" :data="expenses"></c-apex-line-chart>
            </div>
        </div>
        <div v-if="!loading && incomes.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">الواردات و النفقات</h1>
            <div class="card-body">
                <c-apex-line-chart
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
                ></c-apex-line-chart>
            </div>
        </div>
        <div v-if="!loading && debts.length" class="card col-span-full">
            <h1 class="text-lg font-semibold card-title">المبالغ المتبقية</h1>
            <div class="card-body">
                <c-apex-line-chart v-if="debts.length" label="<b class='mr-1'>ديون</b>" :format-tooltip-title="['المبالغ المتبقية']" :suggested-max="suggestedMax(debts)" color="default" :data="debts"></c-apex-line-chart>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue"
import axios from "axios"

const passwordCorrect = ref(false)
const password = ref("")
const loading = ref(false)
const year = ref("")
const month = ref("")
const day = ref("")
const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
const days = ref([])
const years = computed(() => {
    const currentYear = new Date().getFullYear()
    const years = [currentYear]
    for (let i = 1; i < 5; i++) {
        years.push(currentYear - i)
    }
    console.log(years, currentYear)
    return years
})
const patientsTotalCount = ref(0)
const incomeTotal = ref(0)
const expensesTotal = ref(0)
const totalPatients = ref(0)
const totalDebts = ref(0)
const expensesSum = ref(0)
const incomesSum = ref(0)
const patients = ref([])
const visits = ref([])
const expenses = ref([])
const debts = ref([])
const incomes = ref([])

const checkPassword = () => {
    passwordCorrect.value = password.value === "1256" // Replace with your own logic
}

const getData = async () => {
    await fetchData()
}

const fetchData = async () => {
    loading.value = true
    const query = []
    let queryParams = ""
    if (year.value) {
        query.push("year=" + year.value)
        query.push("year=" + year.value)
    }
    if (month.value) {
        query.push("month=" + month.value)
        query.push("month=" + month.value)
    }
    if (day.value && month.value) {
        query.push("day=" + day.value)
        query.push("day=" + day.value)
    }
    queryParams = query.join("&")
    await axios
        .get("/api/statistics?" + queryParams)
        .then(({ data }) => {
            expenses.value = data.expenses
            visits.value = data.visits
            incomes.value = data.incomes
            patients.value = data.patients
            debts.value = data.debts

            totalPatients.value = patients.value.reduce((sum, item) => sum + +item.value, 0)
            expensesSum.value = expenses.value.reduce((sum, item) => sum + +item.value, 0)
            incomesSum.value = incomes.value.reduce((sum, item) => sum + +item.value, 0)

            patientsTotalCount.value = data.patientsTotalCount
            expensesTotal.value = data.expensesTotal
            incomeTotal.value = data.incomeTotal
            totalDebts.value = data.totalDebts
        })
        .finally(() => {
            loading.value = false
        })
    // Mock data fetching logic
    console.log(`Fetching data for year: ${year.value}, month: ${month.value}, day: ${day.value}`)
}

const monthChanged = () => {
    days.value = month.value ? Array.from({ length: 31 }, (_, i) => i + 1) : []
}

const suggestedMax = (timeseries) => {
    const data = timeseries.map((point) => point.value)

    return Math.max(Math.max(...data), 1)
}
</script>

<style scoped>
/* Add your custom styles here */
</style>
