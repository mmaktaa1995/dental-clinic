<template>
    <div class="flex-1 relative pb-8 z-0 overflow-y-auto">
        <div class="flex-1 relative pb-8 z-0 overflow-y-auto">
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8 divide-y divide-dashed divide-gray-200">
                    <div class="py-3">
                        <slot name="header"></slot>
                    </div>
                    <div class="py-4 md:flex md:items-center md:flex-wrap md:justify-between">
                        <slot name="filters"></slot>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 mb-32">
            <div class="flex flex-col mt-2">
                <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                    <table class="datatable bg-white min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                                <th v-for="column in columns" :key="`header-${column.field}`" class="py-2 px-3 text-right ltr:text-left">
                                    {{ column.headerName }}
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="!store.isLoading" class="divide-y divide-gray-200">
                            <template v-if="store.entries?.length">
                                <template v-for="entry in store.entries" :key="entry.id">
                                    <tr class="tr" :class="{ clickable: rowClickable }" @click="rowClicked(entry)">
                                        <template v-for="column in columns" :key="column.field">
                                            <CellRenderer :column="column" :entry="entry"></CellRenderer>
                                        </template>
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <tr class="tr">
                                    <td class="td text-center" :colspan="columns.length">لم يتم العثور على إدخالات حتى الآن.</td>
                                </tr>
                            </template>
                        </tbody>
                        <TableLoader v-else :row-count="5" :column-count="columns.length"></TableLoader>
                    </table>
                    <Pagination :store="store"></Pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Component, onBeforeUnmount } from "vue"
import { EntryListStore } from "@/store/factories/entryListStore"
import Pagination from "@/components/table/Pagination.vue"
import CellRenderer from "@/components/table/CellRenderer.vue"
import TableLoader from "@/components/table/TableLoader.vue"

type CellRendererType = (rowData: any) => HTMLElement | Component
export type DataTableColumn = {
    headerName: string
    field: string
    isHtml?: boolean
    cellRenderer?: CellRendererType
}

const props = withDefaults(
    defineProps<{
        store: EntryListStore
        columns: DataTableColumn[]
        hide?: boolean
        rowClickable?: boolean
        showDatesFilters?: boolean
    }>(),
    {
        hide: false,
        showDatesFilters: false,
        rowClickable: true,
    },
)

const $emit = defineEmits(["rowClicked"])

onBeforeUnmount(() => {
    props.store.$reset()
})

const rowClicked = (row: any) => {
    $emit("rowClicked", row)
}
</script>
