<template>
    <div class="w-full">
        <CSearch>
            <template #head>
                <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                    <th class="py-2 px-3 text-right">{{ $t("debits.name") }}</th>
                    <th class="py-2 px-3 text-right">{{ $t("debits.amount") }}</th>
                    <th class="py-2 px-3 text-right">{{ $t("debits.actionTaken") }}</th>
                    <th class="py-2 px-3 text-right">{{ $t("debits.date") }}</th>
                    <th class="py-2 px-3 text-right"></th>
                </tr>
            </template>
            <template #row="{ entry }">
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.patient.name }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-red-500 font-semibold">
                    {{ entry.remaining_amount }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.visit.notes }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.date }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    <div class="flex item-center">
                        <router-link
                            :to="{
                                name: `patients-files-show`,
                                params: { id: entry.patient.id },
                                query: entry.filters,
                            }"
                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"
                        >
                            <c-icon-eye size="6" class="text-gray-400 hover:text-blue-500 transition-colors" />
                        </router-link>
                    </div>
                </td>
            </template>
        </CSearch>
    </div>
</template>

<script setup></script>
