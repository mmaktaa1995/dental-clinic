<template>
  <!-- eslint-disable vue/no-mutating-props -->
  <div class="flex-1 relative z-0 overflow-y-auto">
    <div class="px-4 py-3 flex justify-between items-center gap-2">
      <div class="flex-1">
        <slot name="header"></slot>
      </div>
      <div class="flex items-center gap-2" v-if="!hideControls || $slots.controls">
        <TableControls
          v-if="!hideControls"
          :table-id="tableId"
          :columns="columns"
          :filters="store.filters"
          @columns-updated="updateVisibleColumns"
          @bookmark-applied="handleBookmarkApplied"
        />
        <slot name="controls"></slot>
      </div>
    </div>
    <div class="bg-white shadow rounded-xl overflow-hidden m-4">
      <!-- Table Controls -->
      <div v-if="$slots.filters" class="px-4 py-6 border-b border-gray-200">
        <slot name="filters"></slot>
      </div>
      <div class="overflow-x-auto">
        <!-- Table Content -->
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="">
            <tr>
              <th
                v-if="selectable"
                class="px-6 py-2.5 text-left text-xs font-medium text-gray-500 w-10"
              >
                <input
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  :checked="selectAllRows === true"
                  :indeterminate.prop="selectAllRows === null"
                  @change="(selectAllRows = selectAllRows === false ? true : false)"
                />
              </th>
              <th
                v-for="column in computedColumns"
                :key="column.field"
                :class="[
                  'px-6 py-2.5 text-left text-xs font-medium text-gray-500',
                  column.sortable ? 'cursor-pointer' : '',
                ]"
                @click="column.sortable ? toggleSort(column.field) : null"
              >
                <div class="flex items-center">
                  {{ column.headerName }}
                  <template v-if="column.sortable">
                    <span
                      v-if="store.order.by !== column.field"
                      class="flex flex-col opacity-0 transition peer-hover:opacity-100"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-3 inline-block relative -bottom-[4px]"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                      >
                        <path d="M12 8l6 8H6l6-8z" />
                      </svg>
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-3 inline-block relative -top-[4px]"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                      >
                        <path d="M12 16l-6-8h12l-6 8z" />
                      </svg>
                    </span>
                    <span v-if="store.order.by === column.field">
                      <svg
                        v-if="store.order.desc"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 inline-block"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                      >
                        <path d="M12 16l-6-8h12l-6 8z" />
                      </svg>
                      <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 inline-block"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                      >
                        <path d="M12 8l6 8H6l6-8z" />
                      </svg>
                    </span>
                  </template>
                </div>
              </th>
              <th
                v-if="actions && actions.length > 0"
                class="px-6 py-2.5 text-right text-xs font-medium text-gray-500"
              >
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="store.loading">
              <td
                :colSpan="(selectable ? 1 : 0) + computedColumns.length + (actions?.length ? 1 : 0)"
                class="px-6 py-2.5 whitespace-nowrap"
              >
                <TableLoader />
              </td>
            </tr>
            <tr v-else-if="!store.loading && entries.length === 0" class="bg-white">
              <td
                :colSpan="(selectable ? 1 : 0) + computedColumns.length + (actions?.length ? 1 : 0)"
                class="px-6 py-2.5 whitespace-nowrap text-sm text-gray-500 text-center"
              >
                {{ $t('global.noEntriesFound') }}
              </td>
            </tr>
            <tr
              v-for="(row, rowIndex) in entries"
              :key="rowIndex"
              :class="['hover:bg-gray-50', rowClickable ? 'cursor-pointer' : '']"
              @click="rowClickable ? handleRowClick(row) : undefined"
            >
              <td v-if="selectable" class="px-6 py-2.5 whitespace-nowrap">
                <input
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  :checked="!!selectedRows[row.id]"
                  @click.stop
                  @change="(selectedRows[row.id] = !selectedRows[row.id])"
                />
              </td>
              <td
                v-for="column in computedColumns"
                :key="column.field"
                class="px-6 py-2.5 whitespace-nowrap text-sm"
                :class="[
                  column.textClass,
                  column.textClassCondition && column.textClassCondition(row)
                    ? column.textClassCondition(row)
                    : '',
                  column.cellClass,
                  column.cellClassCondition && column.cellClassCondition(row)
                    ? column.cellClassCondition(row)
                    : '',
                ]"
              >
                <component
                  v-if="column.cellRenderer"
                  :is="column.cellRenderer"
                  :value="row[column.field]"
                  :row="row"
                  :column="column"
                />
                <span v-else-if="column.isHtml" v-html="row[column.field]"></span>
                <span v-else>
                  {{
                    column.valueFormatter
                      ? column.valueFormatter(row[column.field], row)
                      : row[column.field]
                  }}
                </span>
              </td>
              <td
                v-if="actions && actions.length > 0"
                class="px-6 py-2.5 whitespace-nowrap text-right text-sm font-medium"
              >
                <div class="flex justify-end space-x-2">
                  <button
                    v-for="(action, actionIndex) in actions"
                    :key="actionIndex"
                    :class="[
                      'px-3 py-1 rounded-md text-sm font-medium',
                      {
                        'bg-blue-600 hover:bg-blue-700 text-white': action.type === 'primary',
                        'bg-green-600 hover:bg-green-700 text-white': action.type === 'success',
                        'bg-red-600 hover:bg-red-700 text-white': action.type === 'danger',
                        'bg-yellow-600 hover:bg-yellow-700 text-white': action.type === 'warning',
                        'bg-gray-600 hover:bg-gray-700 text-white': action.type === 'secondary',
                      },
                    ]"
                    @click.stop="handleActionClicked(action, row)"
                  >
                    {{ action.label }}
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <Pagination
          class="border-t border-gray-200"
          v-if="!disablePagination && store.pagination"
          :store="store"
          @page-changed="page => store.setPage(page)"
        />
      </div>

      <!-- Pagination -->
    </div>
  </div>
</template>

<script setup lang="ts">
import {
  computed,
  ref,
  watch,
  onBeforeUnmount,
  useSlots,
  defineEmits,
  withDefaults,
  defineExpose,
} from 'vue';
import type { Component } from 'vue';
import type { EntryListStore } from '@/store/factories/entryListStore';
import Pagination from '@/components/Table/Pagination.vue';
import TableControls from '@/components/Table/TableControls.vue';
import TableLoader from '@/components/Table/TableLoader.vue';
import type { ButtonType } from '@/components/CButton.vue';
import { useTableSettingsStore } from '@/stores/tableSettings';

type CellRendererType = Component;

export interface DataTableColumn {
  headerName: string;
  field: string;
  isHtml?: boolean;
  textClass?: string;
  textClassCondition?: (rowData: any) => string;
  valueFormatter?: (value: any, entry: any) => any;
  sortable?: boolean;
  cellRenderer?: CellRendererType;
  cellClass?: string;
  cellClassCondition?: (rowData: any) => string;
  visible?: boolean;
}

interface DataTableAction {
  label: string;
  type: ButtonType;
  action: (selectedRows: any[], selectedIds: (string | number)[]) => void;
}

const props = withDefaults(
  defineProps<{
    actions?: DataTableAction[];
    columns: DataTableColumn[];
    hide?: boolean;
    rowClickable?: boolean;
    selectable?: boolean;
    disablePagination?: boolean;
    showDatesFilters?: boolean;
    store: EntryListStore;
    dataKey?: string;
    tableId?: string;
    hideControls?: boolean;
  }>(),
  {
    actions: undefined,
    hide: false,
    dataKey: 'entries',
    selectable: false,
    rowClickable: true,
    disablePagination: false,
    showDatesFilters: false,
    tableId: '',
    hideControls: false,
  }
);

// Expose all props for template usage
const actions = props.actions;
const columns = props.columns;
const hide = props.hide;
const rowClickable = props.rowClickable;
const selectable = props.selectable;
const disablePagination = props.disablePagination;
const showDatesFilters = props.showDatesFilters;
const store = props.store;
const dataKey = props.dataKey;
const tableId = props.tableId;
const hideControls = props.hideControls;

const emit = defineEmits(['row-clicked']);

const selectedRows = ref<Record<string, boolean>>({});
const selectAllRows = ref<boolean | null>(false);
const visibleColumns = ref<Record<string, boolean>>({});

// Initialize visible columns
const initVisibleColumns = () => {
  if (columns && columns.length > 0) {
    columns.forEach(column => {
      if (column.visible !== false) {
        visibleColumns.value[column.field] = true;
      }
    });
  }
};
initVisibleColumns();

const computedColumns = computed(() => {
  return columns
    .filter(col => visibleColumns.value[col.field])
    .map(column => {
      return {
        ...column,
        sortable: typeof column.sortable !== 'undefined' ? column.sortable : true,
      };
    });
});

const entries = computed<any[]>(() => {
  if (!store) return [];
  const value = getNestedValue(store, dataKey || 'entries', undefined);
  return value || [];
});

function getNestedValue(obj: any, path: string, defaultValue: any = undefined): any {
  const keys = path.split('.');
  let result = obj;
  for (const key of keys) {
    result = result?.[key];
    if (result === undefined) return defaultValue;
  }
  return result ?? defaultValue;
}

function handleRowClick(row: any) {
  if (rowClickable) {
    emit('row-clicked', row);
  }
}

function handleActionClicked(action: DataTableAction, row: any) {
  action.action([row], [row.id]);
}

function updateVisibleColumns(newVisible: string[]) {
  const newVisibleColumns: Record<string, boolean> = {};
  newVisible.forEach(field => {
    newVisibleColumns[field] = true;
  });
  visibleColumns.value = newVisibleColumns;
}

function handleBookmarkApplied(filters: Record<string, any>) {
  if (store) {
    Object.entries(filters).forEach(([key, value]) => {
      Object.entries(store.filters).forEach(([sKey, sValue]) => {
        if (filters[sKey]) {
          store[key] = value;
        }
      });
    });
    store.loadData();
  }
}

function toggleSort(field: string) {
  if (!store) return;
  if (store.order.by === field) {
    store.order.desc = !store.order.desc;
  } else {
    store.order.by = field;
    store.order.desc = false;
  }
  //   store.fetch();
}

function toggleSelectAll(checked: boolean) {
  entries.value.forEach((entry: any) => {
    if (entry.id !== undefined) {
      selectedRows.value[entry.id] = checked;
    }
  });
}

watch(selectAllRows, newValue => {
  if (newValue !== null) {
    toggleSelectAll(newValue);
  }
});

function getSelectedRows() {
  return Object.entries(selectedRows.value)
    .filter(([_, isSelected]) => isSelected)
    .map(([id]) => entries.value.find((e: any) => e.id === id))
    .filter(Boolean);
}

defineExpose({ getSelectedRows, updateVisibleColumns });

onBeforeUnmount(() => {
  if (store?.$reset) {
    store.$reset();
  }
});
</script>
<!-- import type { ButtonType } from '@/components/CButton.vue'
import { useTableSettingsStore } from '@/stores/tableSettings'

type CellRendererType = Component

interface DataTableColumn {
  headerName: string
  field: string
  isHtml?: boolean
  textClass?: string
  textClassCondition?: (rowData: any) => string
  valueFormatter?: (value: any, entry: any) => any
  sortable?: boolean
  cellRenderer?: CellRendererType
  cellClass?: string
  cellClassCondition?: (rowData: any) => string
  visible?: boolean
}

interface DataTableAction {
  label: string
  type: ButtonType
  action: (selectedRows: any[], selectedIds: (string | number)[]) => void
}

// Props
const props = withDefaults(defineProps<{
  actions?: DataTableAction[]
  columns: DataTableColumn[]
  hide?: boolean
  rowClickable?: boolean
  selectable?: boolean
  disablePagination?: boolean
  showDatesFilters?: boolean
  store: EntryListStore
  dataKey?: string
  tableId?: string
  hideControls?: boolean
}>(), {
  actions: undefined,
  hide: false,
  dataKey: 'entries',
  selectable: false,
  rowClickable: true,
  disablePagination: false,
  showDatesFilters: false,
  tableId: '',
  hideControls: false
})

// Emits
const emit = defineEmits<{
  (e: 'row-clicked', row: any): void
}>()

// Slots and Stores
const slots = useSlots()
const tableSettingsStore = useTableSettingsStore()

// Refs
const selectedRows = ref<Record<string, boolean>>({})
const selectAllRows = ref<boolean | null>(false)
const visibleColumns = ref<Record<string, boolean>>({})

// Types
type CellRendererType = Component

export interface DataTableColumn {
  headerName: string
  field: string
  isHtml?: boolean
  textClass?: string
  textClassCondition?: (rowData: any) => string
  valueFormatter?: (value: any, entry: any) => any
  sortable?: boolean
  cellRenderer?: CellRendererType
  cellClass?: string
  cellClassCondition?: (rowData: any) => string
  visible?: boolean
}

export interface DataTableAction {
  label: string
  type: ButtonType
  action: (selectedRows: any[], selectedIds: (string | number)[]) => void
}

// Props
const props = withDefaults(defineProps<{
  actions?: DataTableAction[]
  columns: DataTableColumn[]
  hide?: boolean
  rowClickable?: boolean
  selectable?: boolean
  disablePagination?: boolean
  showDatesFilters?: boolean
  store: EntryListStore
  dataKey?: string
  tableId?: string
  hideControls?: boolean
}>(), {
  actions: undefined,
  hide: false,
  dataKey: 'entries',
  selectable: false,
  rowClickable: true,
  disablePagination: false,
  showDatesFilters: false,
  tableId: '',
  hideControls: false
})

// Emits
const emit = defineEmits<{
  (e: 'row-clicked', row: any): void
}>()

// Slots
const slots = useSlots()
const tableSettingsStore = useTableSettingsStore()



// Props
const props = withDefaults(defineProps<{
  actions?: DataTableAction[]
  columns: DataTableColumn[]
  hide?: boolean
  rowClickable?: boolean
  selectable?: boolean
  disablePagination?: boolean
  showDatesFilters?: boolean
  store: EntryListStore
  dataKey?: string
  tableId?: string
  hideControls?: boolean
}>(), {
  actions: undefined,
  hide: false,
  dataKey: 'entries',
  selectable: false,
  rowClickable: true,
  disablePagination: false,
  showDatesFilters: false,
  tableId: '',
  hideControls: false
})


// Initialize visible columns from props
const initVisibleColumns = () => {
  if (props.columns && props.columns.length > 0) {
    props.columns.forEach(column => {
      if (column.visible !== false) {
        visibleColumns.value[column.field] = true
      }
    })
  }
}

initVisibleColumns()

// Computed properties
const computedColumns = computed<DataTableColumn[]>(() => {
  return props.columns.filter(col => visibleColumns.value[col.field] !== false)
})

const entries = computed<any[]>(() => {
  if (!props.store) return []
  const value = getNestedValue(props.store, props.dataKey || 'entries', undefined)
  return value || []
})

// Methods
const getNestedValue = (obj: any, path: string, defaultValue: any = undefined): any => {
  const keys = path.split('.')
  let result = obj
  
  for (const key of keys) {
    result = result?.[key]
    if (result === undefined) return defaultValue
  }
  
  return result ?? defaultValue
}

// Watch for selectAllRows changes
watch(selectAllRows, (newValue) => {
  if (newValue !== null) {
    toggleSelectAll(newValue)
  }
})

const handleRowClick = (row: any) => {
  if (props.rowClickable) {
    emit('row-clicked', row)
  }
}

const handleActionClicked = (action: DataTableAction) => {
  const selectedEntries = Object.entries(selectedRows.value)
    .filter(([_, isSelected]) => isSelected)
    .map(([id]) => entries.value.find((e: any) => e.id === id))
    .filter(Boolean)
  
  action.action(selectedEntries, selectedEntries.map((e: any) => e.id))
}

const updateVisibleColumns = (columns: string[]) => {
  // Update visible columns based on the provided array of column fields
  const newVisibleColumns: Record<string, boolean> = {}
  columns.forEach(field => {
    newVisibleColumns[field] = true
  })
  visibleColumns.value = newVisibleColumns
}

const handleBookmarkApplied = (filters: Record<string, any>) => {
  if (props.store) {
    // Update store filters
    Object.entries(filters).forEach(([key, value]) => {
      props.store.filters[key] = value
    })
    // Trigger fetch with updated filters
    props.store.fetch()
  }
}

const toggleSort = (field: string) => {
  if (!props.store) return
  
  if (props.store.order.by === field) {
    props.store.order.desc = !props.store.order.desc
  } else {
    props.store.order.by = field
    props.store.order.desc = false
  }
  
  props.store.fetch()
}

// Handle select all rows
const toggleSelectAll = (checked: boolean) => {
  entries.value.forEach((entry: any) => {
    if (entry.id !== undefined) {
      selectedRows.value[entry.id] = checked
    }
  })
}

// Watch for selectAllRows changes
watch(selectAllRows, (newValue) => {
  if (newValue !== null) {
    toggleSelectAll(newValue)
  }
})


// Cleanup on unmount
onBeforeUnmount(() => {
  if (props.store?.$reset) {
    props.store.$reset()
  }
})


const updateSelectAllState = () => {
  const selectedCount = Object.values(selectedRows.value).filter(Boolean).length
  if (selectedCount === 0) {
    selectAllRows.value = false
  } else if (selectedCount === entries.value.length) {
    selectAllRows.value = true
  } else {
    selectAllRows.value = null
  }
}

// Watchers
watch(
  selectedRows,
  () => {
    updateSelectAllState()
  },
  { deep: true }
)

// Lifecycle
onBeforeUnmount(() => {
// Expose methods
defineExpose({
  getSelectedRows: () => {
    return Object.entries(selectedRows.value)
      .filter(([_, isSelected]) => isSelected)
      .map(([id]) => entries.value.find((e: any) => e.id === id))
      .filter(Boolean)
  },
  updateVisibleColumns,
  saveCurrentView: (name: string) => {
    if (!props.tableId || !props.store) return
    
    // Use the table settings store to save the current view
    tableSettingsStore.saveView({
      tableId: props.tableId,
      name,
      state: {
        visibleColumns: Object.entries(visibleColumns.value)
          .filter(([_, isVisible]) => isVisible)
          .map(([field]) => field),
        filters: { ...props.store.filters }
      }
    })
  }
})

// Cleanup on unmount
onBeforeUnmount(() => {
  if (props.store?.$reset) {
    props.store.$reset()
  }
})
</script>

<style scoped>
/* Add any custom styles here */
</style> -->
