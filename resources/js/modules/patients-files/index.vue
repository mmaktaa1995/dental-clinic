<template>
    <div v-if="!isPatientFilesDetails" class="w-full">
        <CSearch>
            <template #head>
                <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                    <th class="py-2 px-3 text-right">الاسم</th>
                    <th class="py-2 px-3 text-right">رقم الملف</th>
                    <th class="py-2 px-3 text-right">تاريخ اخر دفعة</th>
                    <th class="py-2 px-3 text-right">اخر دفعة</th>
                    <th class="py-2 px-3 text-right">المبلغ المتبقي</th>
                    <th class="py-2 px-3 text-right"></th>
                </tr>
            </template>
            <template #row="{ entry }">
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.patient.name }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.patient.file_number }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.latest_payment_date }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ +entry.latest_payment }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-pink-600">
                    {{ +entry.total_remaining_amount }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    <div class="flex item-center">
                        <router-link
                            :to="{
                                name: `patients-files-show`,
                                params: { id: entry.patient.id },
                                query: entry.filters,
                            }"
                            tag="a"
                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"
                        >
                            <c-icon-eye size="6" class="text-gray-400 hover:text-blue-500 transition-colors" />
                        </router-link>
                    </div>
                </td>
            </template>
        </CSearch>
        <router-view></router-view>
    </div>
    <div v-else class="w-full">
        <router-view></router-view>
    </div>
</template>

<script setup>
import { computed } from "vue"
import { useRoute } from "vue-router"

const route = useRoute()
const isPatientFilesDetails = computed(() => {
    return route.name === "patients-files-show"
})
</script>
