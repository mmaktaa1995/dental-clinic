<template>
    <div class="w-full">
        <search>
            <template slot="filters" slot-scope="{ filters, loadEntries }">
                <div class="grid grid-cols-2 gap-6 min-w-0 w-full mt-3">
                    <div class="w-1/">
                        <label for="date-input" class="block text-sm font-medium leading-5 text-gray-700">
                            التاريخ
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <date-picker
                                v-model.lazy="filters.date"
                                v-on:change="loadEntries"
                                id="date-input"
                                class=""
                            ></date-picker>
                        </div>
                    </div>
                    <div class="">
                        <label for="patient_id"
                               class="block text-sm font-medium text-gray-700 text-right">المريض</label>
                        <select id="patient_id" autocomplete="off" v-model="filters.patient_id"
                                v-on:change="loadEntries"
                                class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-2 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full">
                            <option value="">اختر مريض</option>
                            <option :value="id" v-for="(name, id) in patients">{{ name }}</option>
                        </select>
                    </div>
                </div>
            </template>

            <template slot="troubleshooting">
                <p>It looks like there was an error. Please check your application logs.</p>

                <p class="mt-2">
                    Consider searching using a more recent "Starting from" date. The CloudWatch API may have long
                    response
                    times while searching far into the past. These requests may timeout or lead to unexpected errors.
                </p>
            </template>

            <template slot="create-btn">
                <router-link :to="{ name: `payments-create`}"
                             class="ml-4 flex items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none">
                    إضافة
                </router-link>
            </template>
            <template slot="head">
                <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                    <th class="py-2 px-3 text-right">الاسم</th>
                    <th class="py-2 px-3 text-right">المبلغ</th>
                    <th class="py-2 px-3 text-right">التاريخ</th>
                </tr>
            </template>
            <template slot="row" slot-scope="{ entry }">
                <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.patient.name }}
                </td>
                <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.amount | numberFormat }}
                </td>
                <td class="px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.date }}
                </td>
            </template>
        </search>
        <router-view></router-view>
    </div>
</template>

<script>

import axios from "axios";
import DatePicker from 'vue2-datepicker';

export default {
    data() {
        return {
            patients: []
        }
    },
    mounted() {
        axios.get('/api/patients/dropdown').then(({data}) => {
            this.patients = data;
        })
    },
    components: { DatePicker },
};
</script>
