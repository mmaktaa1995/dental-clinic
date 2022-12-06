<template>
    <div class="w-full">
        <search>
            <template slot="filters" slot-scope="{ filters, loadEntries }"></template>

            <template slot="troubleshooting">
                <p>It looks like there was an error. Please check your application logs.</p>

                <p class="mt-2">
                    Consider searching using a more recent "Starting from" date. The CloudWatch API may have long
                    response
                    times while searching far into the past. These requests may timeout or lead to unexpected errors.
                </p>
            </template>

            <template slot="create-btn">
            </template>
            <template slot="head">
                <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                    <th class="py-2 px-3 text-right">الاسم</th>
                    <th class="py-2 px-3 text-right">المبلغ</th>
                    <th class="py-2 px-3 text-right">الإجراء الذي تم</th>
                    <th class="py-2 px-3 text-right">التاريخ</th>
                    <th class="py-2 px-3 text-right"></th>
                </tr>
            </template>
            <template slot="row" slot-scope="{ entry }">
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.patient.name }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-red-500 font-semibold">
                    {{ entry.remaining_amount | numberFormat}}
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
                            params: { id: entry.patient.id},
                            query: entry.filters,
                        }"
                            tag="a"
                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"
                        >
                            <icon-eye
                                size="6"
                                class=" text-gray-400 hover:text-blue-500 transition-colors"
                            />
                        </router-link>
                    </div>
                </td>
            </template>
        </search>
        <router-view></router-view>
    </div>
</template>

<script>

export default {
};
</script>
