<template>
    <div class="w-full">
        <CSearch>
            <template #filters="{ filters, loadEntries }">
                <div class="grid grid-cols-2 gap-6 min-w-0 w-full mt-3">
                    <div class="w-1/">
                        <label for="date-input" class="block text-sm font-medium leading-5 text-gray-700"> التاريخ </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input id="date-input" v-model.lazy="filters.date" class="" @change="loadEntries" />
                        </div>
                    </div>
                    <div class="">
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 text-right">المريض</label>
                        <select id="patient_id" v-model="filters.patient_id" autocomplete="off" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-2 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" @change="loadEntries">
                            <option value="">اختر مريض</option>
                            <option v-for="(name, id) in patients" :key="id" :value="id">{{ name }}</option>
                        </select>
                    </div>
                </div>
            </template>

            <template #create-btn>
                <router-link :to="{ name: `payments-create` }" class="ml-4 flex items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none">
                    إضافة
                </router-link>
            </template>
            <template #head>
                <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                    <th class="py-2 px-3 text-right">الاسم</th>
                    <th class="py-2 px-3 text-right">المبلغ</th>
                    <th class="py-2 px-3 text-right">التاريخ</th>
                </tr>
            </template>
            <template #row="{ entry }">
                <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.patient.name }}
                </td>
                <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.amount }}
                </td>
                <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.date }}
                </td>
            </template>
        </CSearch>
        <router-view></router-view>
    </div>
</template>

<script>
import axios from "axios"

export default {
    data() {
        return {
            patients: [],
        }
    },
    mounted() {
        axios.get("/api/patients/dropdown").then(({ data }) => {
            this.patients = data
        })
    },
}
</script>
