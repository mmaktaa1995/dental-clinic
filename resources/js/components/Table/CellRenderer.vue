<template>
    <template v-if="column.cellRenderer">
        <td class="td">
            <component :is="column.cellRenderer" v-if="isComponent" :value="getValue" :entry :column></component>
            <div v-if="isFunction" v-html="column.cellRenderer(entry)"></div>
        </td>
    </template>
    <template v-else>
        <td v-if="column.isHtml" class="td" :class="{ [column.textClass]: column.textClass }" v-html="getValue"></td>
        <td v-else class="td" :class="{ [column.textClass]: column.textClass }">
            {{ getValue }}
        </td>
    </template>
</template>

<script setup lang="ts">
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import { computed, reactive } from "vue"

const props = defineProps<{
    column: DataTableColumn
    entry: any
}>()

const { textClass, textClassCondition } = reactive(props.column)

const setTextClass = computed(() => {
    if (typeof textClassCondition === "undefined") {
        return !!textClass
    }
    return textClassCondition(props.entry)
})

const isComponent = computed(() => {
    return props.column.cellRenderer && typeof props.column.cellRenderer !== "function"
})
const isFunction = computed(() => {
    return props.column.cellRenderer && typeof props.column.cellRenderer === "function"
})

function getNestedValue(obj, keyPath, defaultValue = undefined) {
    return keyPath.split(".").reduce((current, key) => {
        return current && key in current ? current[key] : defaultValue
    }, obj)
}

const getValue = computed(() => {
    let value = getNestedValue(props.entry, props.column.field)
    if (props.column.valueFormatter) {
        value = props.column.valueFormatter(value, props.entry)
    }
    return value
})
</script>
