<template>
    <template v-if="column.cellRenderer">
        <component :is="column.cellRenderer" v-if="isComponent" :entry :column></component>
        <div v-if="isFunction" v-html="column.cellRenderer(entry)"></div>
    </template>
    <template v-else>
        <td v-if="column.isHtml" class="td" v-html="entry[column.field]"></td>
        <td v-else class="td">
            {{ entry[column.field] }}
        </td>
    </template>
</template>

<script setup lang="ts">
import { DataTableColumn } from "@/components/table/DataTable.vue"
import { computed } from "vue"

const props = defineProps<{
    column: DataTableColumn
    entry: any
}>()

const isComponent = computed(() => {
    return props.column.cellRenderer && typeof props.column.cellRenderer !== "function"
})
const isFunction = computed(() => {
    return props.column.cellRenderer && typeof props.column.cellRenderer === "function"
})
</script>
