<template>
    <!-- eslint-disable vue/no-mutating-props -->
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
                                <th v-if="selectable" :key="`header-0`" class="py-2 px-3 text-right ltr:text-left">
                                    <CCheckbox v-model="selectAllRows" :indeterminate="selectAllRows === null"></CCheckbox>
                                </th>
                                <th v-for="column in computedColumns" :key="`header-${column.field}`" class="py-2.5 px-3 text-right ltr:text-left" :class="{ 'cursor-pointer': column.sortable }" @click="column.sortable && toggleSort(column.field)">
                                    <div class="flex items-center gap-1">
                                        <span class="peer">{{ column.headerName }}</span>
                                        <template v-if="column.sortable">
                                            <span v-if="store.order.by !== column.field" class="flex flex-col opacity-0 transition peer-hover:opacity-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block relative -bottom-[4px]" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 8l6 8H6l6-8z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block relative -top-[4px]" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 16l-6-8h12l-6 8z" />
                                                </svg>
                                            </span>
                                            <span v-if="store.order.by === column.field">
                                                <svg v-if="store.order.desc" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 16l-6-8h12l-6 8z" />
                                                </svg>
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 8l6 8H6l6-8z" />
                                                </svg>
                                            </span>
                                        </template>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="!store.isLoading" class="divide-y divide-gray-200">
                            <template v-if="selectable && hasSelectedRows && actions?.length">
                                <tr>
                                    <td :colspan="columns.length">
                                        <div class="flex items-center gap-2 py-1 px-3">
                                            <CButton v-for="action in actions" :key="action.label" sm :type="action.type" @click="actionClicked(action)">
                                                {{ action.label }}
                                            </CButton>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template v-if="store.entries?.length">
                                <template v-for="entry in store.entries" :key="entry.id">
                                    <tr class="tr" :class="{ clickable: rowClickable }" @click="rowClicked(entry)">
                                        <td v-if="selectable" class="td">
                                            <CCheckbox v-model="selectedRows[entry.id]"></CCheckbox>
                                        </td>
                                        <template v-for="column in columns" :key="column.field">
                                            <CellRenderer :column="column" :entry="entry"></CellRenderer>
                                        </template>
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <tr class="tr">
                                    <td class="td text-center" :colspan="columns.length">
                                        {{ $t("global.noEntriesFound") }}
                                    </td>
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
/* eslint-disable vue/no-mutating-props */
import { Component, computed, onBeforeUnmount, reactive, ref, watch } from "vue"
import { EntryListStore } from "@/store/factories/entryListStore"
import Pagination from "@/components/table/Pagination.vue"
import CellRenderer from "@/components/table/CellRenderer.vue"
import TableLoader from "@/components/table/TableLoader.vue"
import { ButtonType } from "@/components/CButton.vue"

type CellRendererType = (rowData: any) => HTMLElement | Component
export type DataTableColumn = {
    headerName: string
    field: string
    isHtml?: boolean
    sortable?: boolean
    cellRenderer?: CellRendererType
}

export type DataTableAction = {
    label: string
    type: ButtonType
    action: (selectedRows: any[], selectedIds: number[]) => void
}

const props = withDefaults(
    defineProps<{
        actions?: DataTableAction[]
        columns: DataTableColumn[]
        hide?: boolean
        rowClickable?: boolean
        selectable?: boolean
        showDatesFilters?: boolean
        store: EntryListStore
    }>(),
    {
        // eslint-disable-next-line
        actions: [] as DataTableAction[],
        hide: false,
        selectable: false,
        showDatesFilters: false,
        rowClickable: true,
    },
)

const $emit = defineEmits(["rowClicked", "getSelectedRows"])

const selectAllRows = ref<boolean | null>(false)
const selectedRows = reactive<Record<string, boolean>>({})

const computedColumns = computed(() => {
    return [...props.columns].map((column) => {
        return {
            ...column,
            sortable: typeof column.sortable !== "undefined" ? column.sortable : true,
        }
    })
})

onBeforeUnmount(() => {
    props.store.$reset()
})

watch(
    () => selectAllRows.value,
    (selectAllRows) => {
        if (selectAllRows === true) {
            props.store.entries?.forEach((entry) => {
                selectedRows[entry.id] = true
            })
        } else if (selectAllRows === false) {
            props.store.entries?.forEach((entry) => {
                selectedRows[entry.id] = false
            })
        }
    },
)

const updateSelectAllState = () => {
    const entries = props.store.entries || []
    const selectedCount = entries.filter((entry) => selectedRows[entry.id]).length

    if (selectedCount === 0) {
        selectAllRows.value = false
    } else if (selectedCount === entries.length) {
        selectAllRows.value = true
    } else {
        selectAllRows.value = null // Indeterminate state
    }
}

watch(
    () => selectedRows,
    () => {
        updateSelectAllState()
    },
    { deep: true },
)

const hasSelectedRows = computed(() => {
    return Object.values(selectedRows).some((selectedRow) => !!selectedRow)
})

const rowClicked = (row: any) => {
    $emit("rowClicked", row)
}

const actionClicked = (action: DataTableAction) => {
    console.log(action)
    const selectedRowsIds = Object.entries(selectedRows)
        .filter(([, value]) => !!value)
        .map(([id]) => +id)
    const selectedRowsEntries = props.store.entries!.filter((entry) => selectedRowsIds.includes(entry.id))
    action.action(selectedRowsEntries, selectedRowsIds)
}

const toggleSort = (field: string) => {
    if (props.store.order.by === field) {
        if (props.store.order.desc) {
            props.store.order.desc = false
        } else {
            props.store.order.by = null
            props.store.order.desc = false
        }
    } else {
        props.store.order.by = field
        props.store.order.desc = true
    }
}
</script>
