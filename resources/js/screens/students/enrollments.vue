<template>
    <div class="w-full">
        <search hide="true">
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
                    <th class="py-2 px-2 text-left">Full Name</th>
                    <th class="py-2 px-2 text-left">Course</th>
                    <th class="py-2 px-2 text-left">Instructor</th>
                    <th class="py-2 px-2 text-left">Section No.</th>
                    <th class="py-2 px-2 text-left">Grade</th>
                    <th class="py-2 px-2 text-left">Created At</th>
                </tr>
            </template>
            <template slot="row" slot-scope="{ entry }">
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-bold text-gray-500">
                    {{ entry.student.full_name }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium text-gray-500">
                    {{ entry.course.title }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.section.instructor.full_name }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.section.section_no }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.grade }}
                </td>
                <td class="px-2 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                    {{ entry.created_at }}
                </td>
            </template>
        </search>
        <router-view></router-view>
    </div>
</template>

<script>

export default {};
</script>
