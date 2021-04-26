<template>
    <div class="w-full">
        <search :hide="true">
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
                    <th class="py-2 px-2 text-left">Course</th>
                    <th class="py-2 px-2 text-left">Instructor</th>
                    <th class="py-2 px-2 text-left">Hours</th>
                    <th class="py-2 px-2 text-left">Section</th>
                    <th class="py-2 px-2 text-left">Room</th>
                    <th class="py-2 px-2 text-left">Grade</th>
                    <th class="py-2 px-2 text-left">Created At</th>
<!--                    <th class="py-2 px-2 text-left">Actions</th>-->
                </tr>
            </template>
            <template slot="row" slot-scope="{ entry }">
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-bold text-gray-500">
                    {{ entry.course.title }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-bold text-gray-500">
                    {{ entry.section.instructor.full_name }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium text-gray-500">
                    {{ entry.course.hours }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.section.section_no }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.section.room_no }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.grade }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.created_at }}
                </td>
<!--                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">-->
<!--                    <div class="flex item-center">-->
<!--                        <router-link-->
<!--                            :to="{-->
<!--                            name: `courses-details`,-->
<!--                            params: { id: entry.id},-->
<!--                            query: entry.filters,-->
<!--                        }"-->
<!--                            tag="button"-->
<!--                            href="#"-->
<!--                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"-->
<!--                        >-->
<!--                            <icon-eye-->
<!--                                size="5"-->
<!--                                class="mr-3 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500"-->
<!--                            />-->
<!--                        </router-link>-->
<!--                    </div>-->
<!--                </td>-->
            </template>
        </search>
    </div>
</template>

<script>

export default {
};
</script>
