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
                <router-link :to="{ name: `patients-create`}"
                             class="ml-4 flex items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none">
                    إضافة
                </router-link>
            </template>
            <template slot="head">
                <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                    <th class="py-2 px-3 text-right">الاسم</th>
                    <th class="py-2 px-3 text-right">رقم الملف</th>
                    <th class="py-2 px-3 text-right">رقم الموبايل</th>
                    <th class="py-2 px-3 text-right"></th>
                </tr>
            </template>
            <template slot="row" slot-scope="{ entry }">
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.name }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.file_number }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.mobile ? entry.mobile : entry.phone }}
                </td>
                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    <div class="flex item-center">
                        <div class="w-4 mr-2 transform hover:text-purple-500">
                            <router-link
                                :to="{
                                name: `deleted-patients-restore`,
                                params: { id: entry.id},
                                query: {type:'مريض'},
                            }"
                                tag="a"
                                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent transition-colors hover:text-red-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"
                            >
                                <icon-restore
                                    size="5"
                                    class=" text-gray-400 hover:text-red-500 transition-colors"
                                />
                            </router-link>
                        </div>
                    </div>
                </td>
            </template>
        </search>
        <router-view></router-view>
    </div>
</template>

<script>

export default {
    mounted() {
    },
    computed:{
    }
};
</script>
