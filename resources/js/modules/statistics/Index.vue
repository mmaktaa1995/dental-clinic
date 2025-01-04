<template>
    <div class="bg-white pt-4 h-full">
        <div class="mx-8">
            <h3 class="text-base font-semibold text-gray-700">{{ getGreeting }}</h3>
            <p class="text-sm text-gray-500">{{ getFormattedDate }}</p>
        </div>
        <hr class="my-4" />
        <div v-if="!passwordCorrect">
            <div class="max-w-md mx-auto py-4">
                <CTextField v-model="password" :label="$t('auth.password')" name="password" @input="checkPassword"></CTextField>
            </div>
        </div>
        <div v-else class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <div class="col-span-full gap-5 px-8">
                <div class="flex gap-5 mb-4">
                    <div class="w-1/5">
                        <CSelect v-model="year" name="year" :options="years" :label="$t('statistics.selectYear')" :hint="$t('statistics.selectYear')"></CSelect>
                    </div>
                    <div class="w-1/5">
                        <CSelect v-model="month" name="month" :options="months" :label="$t('statistics.selectMonth')" :hint="$t('statistics.selectMonth')" @change="monthChanged"></CSelect>
                    </div>
                    <div v-if="month" class="w-1/5">
                        <CSelect v-model="day" name="day" :options="days" :label="$t('statistics.selectDay')" :hint="$t('statistics.selectDay')"></CSelect>
                    </div>
                    <div class="w-1/5">
                        <CAsyncButton type="info" :loading="loading" @click="getData"> بحث </CAsyncButton>
                    </div>
                </div>
                <div class="flex gap-5">
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
                                        <span
                                            :class="{
                                                'text-green-500': Math.sign(incomesSum - expensesSum) === 1,
                                                'text-red-500': Math.sign(incomesSum - expensesSum) === -1,
                                            }"
                                        >
                                            {{ incomesSum - expensesSum }}</span
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr class="col-span-full" />
            <div class="col-span-full px-8">
                <div class="grid grid-cols-4 gap-4">
                    <template v-for="widget in widgets" :key="widget">
                        <div class="w-full">
                            <div class="relative flex flex-col min-w-0 break-words bg-white rounded-md mb-6 xl:mb-0 shadow-lg">
                                <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                            <h5 class="text-blueGray-400 uppercase font-bold text-xs">{{ widget.label }}</h5>
                                            <span class="font-semibold text-xl text-blueGray-700 mt-3" :class="widget.color">{{ widget.value }}</span>
                                        </div>
                                        <div class="relative w-auto pl-4 flex-initial">
                                            <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full" :class="[widget.iconColor]">
                                                <Component :is="widget.icon" size="6" class="text-white"></Component>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-8 mx-8">
                <div v-if="!loading && patients.length" class="card">
                    <h1 class="text-lg font-semibold card-title">المرضى</h1>
                    <div class="card-body">
                        <c-apex-line-chart v-if="patients.length" label="مريض" :format-tooltip-title="['المرضى']" :data="patients"></c-apex-line-chart>
                    </div>
                </div>
                <div v-if="!loading && visits.length" class="card">
                    <h1 class="text-lg font-semibold card-title">الزيارات</h1>
                    <div class="card-body">
                        <c-apex-line-chart v-if="visits.length" label="زيارة" color="cyan" :format-tooltip-title="['الزيارات']" :data="visits"></c-apex-line-chart>
                    </div>
                </div>
                <!--        <div v-if="!loading && incomes.length" class="card mx-8">-->
                <!--            <h1 class="text-lg font-semibold card-title">إحصائيات الواردات</h1>-->
                <!--            <div class="card-body">-->
                <!--                <c-apex-polar-chart v-if="incomes.length" label="إيراد" :format-tooltip-title="['الواردات']" :data="incomes"></c-apex-polar-chart>-->
                <!--            </div>-->
                <!--        </div>-->
                <div v-if="!loading && incomes.length" class="card">
                    <h1 class="text-lg font-semibold card-title">الواردات</h1>
                    <div class="card-body">
                        <c-apex-line-chart v-if="incomes.length" label="<b class='mr-1'>واردات</b>" color="green" :format-tooltip-title="['الواردات']" :suggested-max="suggestedMax(incomes)" :data="incomes"></c-apex-line-chart>
                    </div>
                </div>
                <div v-if="!loading && expenses.length" class="card">
                    <h1 class="text-lg font-semibold card-title">النفقات</h1>
                    <div class="card-body">
                        <c-apex-line-chart v-if="expenses.length" label="<b class='mr-1'>نفقات</b>" color="red" :format-tooltip-title="['النفقات']" :suggested-max="suggestedMax(expenses)" :data="expenses"></c-apex-line-chart>
                    </div>
                </div>
                <div v-if="!loading && debts.length" class="card">
                    <h1 class="text-lg font-semibold card-title">المبالغ المتبقية</h1>
                    <div class="card-body">
                        <c-apex-line-chart v-if="debts.length" label="<b class='mr-1'>ديون</b>" :format-tooltip-title="['المبالغ المتبقية']" :suggested-max="suggestedMax(debts)" color="default" :data="debts"></c-apex-line-chart>
                    </div>
                </div>
                <div v-if="!loading && incomes.length" class="card col-span-full">
                    <h1 class="text-lg font-semibold card-title">الواردات و النفقات</h1>
                    <div class="card-body">
                        <c-apex-bar-chart
                            v-if="incomes.length"
                            label="واردات"
                            :colors="['blue', 'red']"
                            :format-tooltip-title="['الواردات']"
                            :suggested-max="suggestedMax(incomes)"
                            :data="incomes"
                            :series="[
                                {
                                    name: '<b class=\'mr-1\'>واردات </b>',
                                    data: incomes.map((_) => _.value),
                                },
                                {
                                    name: '<b class=\'mr-1\'>نفقات </b>',
                                    data: expenses.map((_) => _.value),
                                },
                            ]"
                        ></c-apex-bar-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from "vue"
import { api } from "@/logic/api"
import { formatNumber as formatNumberFn } from "@/logic/helpers"
import { useI18n } from "vue-i18n"
import { useAccountStore } from "@/modules/auth/accountStore"
import { format } from "date-fns"

const { t } = useI18n()
const accountStore = useAccountStore()
const today = new Date()
const passwordCorrect = ref(false)
const password = ref("")
const loading = ref(false)
const year = ref(today.getFullYear())
const month = ref<number | null>(null)
const day = ref("")
const months = [
    { id: 1, label: "January" },
    { id: 2, label: "February" },
    { id: 3, label: "March" },
    { id: 4, label: "April" },
    { id: 5, label: "May" },
    { id: 6, label: "June" },
    { id: 7, label: "July" },
    { id: 8, label: "August" },
    { id: 9, label: "September" },
    { id: 10, label: "October" },
    { id: 11, label: "November" },
    { id: 12, label: "December" },
]
const days = ref<{ id: number; label: any }[]>([])
const years = computed(() => {
    const currentYear = new Date().getFullYear()
    const years = [{ id: currentYear, label: currentYear }]
    for (let i = 1; i < 5; i++) {
        years.push({ id: currentYear - i, label: currentYear - i })
    }
    return years
})
const totalPatientsCount = ref(0)
const totalIncome = ref(0)
const totalExpenses = ref(0)
const totalPatients = ref(0)
const totalDebts = ref(0)
const expensesSum = ref(0)
const incomesSum = ref(0)
const patients = ref<{ value: number; label: any }[]>([])
const visits = ref<{ value: number; label: any }[]>([])
const expenses = ref<{ value: number; label: any }[]>([])
const debts = ref<{ value: number; label: any }[]>([])
const incomes = ref<{ value: number; label: any }[]>([])

const widgets = computed(() => {
    const totalGrossSign = Math.sign(totalIncome.value - totalExpenses.value)
    let totalGrossColor = ""
    if (totalGrossSign === 1) {
        totalGrossColor = "text-green-600"
    } else if (totalGrossSign === -1) {
        totalGrossColor = "text-rose-600"
    }

    return [
        {
            label: t("statistics.totalPatients"),
            icon: "CIconUsers",
            iconColor: "bg-red-500",
            color: "text-blueGray-700",
            value: totalPatientsCount.value,
        },
        {
            label: t("statistics.totalIncome"),
            icon: "CIconMoney",
            iconColor: "bg-orange-500",
            color: "text-green-600",
            value: formatNumber(totalIncome.value),
        },
        {
            label: t("statistics.totalExpenses"),
            icon: "CIconExpenses",
            iconColor: "bg-pink-500",
            color: "text-rose-600",
            value: formatNumber(totalExpenses.value),
        },
        {
            label: t("statistics.netGrossProfit"),
            icon: "CIconMoney",
            iconColor: "bg-teal-500",
            color: totalGrossColor,
            value: formatNumber(totalIncome.value - totalExpenses.value),
        },
    ]
})

const checkPassword = () => {
    passwordCorrect.value = password.value === "1256"
    if (passwordCorrect.value) {
        getData()
    }
}

const getData = async () => {
    await fetchData()
}

const fetchData = async () => {
    if (loading.value) {
        return
    }
    loading.value = true
    const query = []
    let queryParams = ""
    if (year.value) {
        query.push("year=" + year.value)
    }
    if (month.value) {
        query.push("month=" + month.value)
    }
    if (day.value && month.value) {
        query.push("day=" + day.value)
    }
    queryParams = query.join("&")
    await api
        .get("/statistics?" + queryParams)
        .then((data) => {
            expenses.value = data.expenses
            visits.value = data.visits
            incomes.value = data.incomes
            patients.value = data.patients
            debts.value = data.debts

            totalPatients.value = patients.value.reduce((sum, item) => sum + +item.value, 0)
            expensesSum.value = expenses.value.reduce((sum, item) => sum + +item.value, 0)
            incomesSum.value = incomes.value.reduce((sum, item) => sum + +item.value, 0)

            totalPatientsCount.value = data.totalPatientsCount
            totalExpenses.value = data.totalExpenses
            totalIncome.value = data.totalIncome
            totalDebts.value = data.totalDebts
        })
        .finally(() => {
            loading.value = false
        })
}

const monthChanged = () => {
    const selectedDate = new Date(year.value, month.value!, 0)
    days.value = month.value
        ? Array.from({ length: selectedDate.getDate() }, (_, i) => ({
              id: i + 1,
              label: i + 1,
          }))
        : []
}

const suggestedMax = (timeSeries: { value: number }[]) => {
    const data = timeSeries.map((point) => point.value)

    return Math.max(Math.max(...data), 1)
}

const formatNumber = (value: any) => {
    return formatNumberFn(value)
}

const getGreeting = computed(() => {
    const now = new Date()
    const hour = now.getHours()

    let greetings = ""
    if (hour < 12) {
        greetings = t("global.greetings.morning")
    } else if (hour < 18) {
        greetings = t("global.greetings.afternoon")
    } else {
        greetings = t("global.greetings.evening")
    }

    return `${greetings} ${accountStore.user.name}`
})

const getFormattedDate = computed(() => {
    return format(today, "EEEE, MMMM d, yyyy") // 'Friday, January 3, 2025'
})
</script>

<style scoped>
/* Add your custom styles here */
</style>
