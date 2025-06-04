<template>
  <div class="flex items-center gap-2">
    <!-- Bookmarks Dropdown -->
    <div class="s-dropdown relative inline-block" v-if="showBookmarks">
      <CButton type="info" id="bookmarks-menu" sm @click="toggleBookmarksDropdown">
        <span> {{ $t('table.bookmarks') }}</span>
        <c-icon class="mr-1 ltr:ml-1 ltr:m-r-0">fa fa-bookmark</c-icon>
      </CButton>

      <!-- Bookmarks Dropdown Menu -->
      <transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <div
          v-show="isBookmarksOpen"
          class="origin-top-right absolute left-0 ltr:right-0 ltr:left-auto ltr:origin-left mt-2 w-56 rounded-md overflow-hidden shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
          role="menu"
          aria-labelledby="bookmarks-menu"
        >
          <div class="pb-1" role="none">
            <div class="px-4 py-2 text-sm text-gray-700 font-medium bg-slate-50 border-b border-gray-100">
              {{ $t('table.saved_views') }}
            </div>
            <template v-if="Object.keys(bookmarks).length > 0">
              <button
                v-for="(bookmark, name) in bookmarks"
                :key="name"
                @click="applyBookmark(name)"
                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex justify-between items-center"
                role="menuitem"
              >
                <span>{{ name }}</span>
                <button @click.stop="deleteBookmark(name)" class="text-gray-400 hover:text-red-500">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </button>
            </template>
            <div v-else class="px-4 py-2 text-sm text-gray-500">
              {{ $t('table.no_bookmarks') }}
            </div>
            <div class="border-t border-gray-100"></div>
            <CButton @click="saveCurrentView" type="flat" class="text-right !text-sky-500" role="menuitem">
              {{ $t('table.save_current_view') }}
            </CButton>
          </div>
        </div>
      </transition>
    </div>

    <!-- Columns Dropdown -->
    <div class="s-dropdown relative inline-block">
      <CButton type="accent" id="columns-menu" sm @click="toggleColumnsDropdown">
        {{ $t('table.columns') }}
        <c-icon class="mr-1 ltr:ml-1 ltr:m-r-0">fa fa-columns</c-icon>
      </CButton>

      <!-- Columns Dropdown Menu -->
      <transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <div
          v-show="isColumnsOpen"
          class="origin-top-left absolute left-0 ltr:right-0 ltr:left-auto ltr:origin-left mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
          role="menu"
          aria-labelledby="columns-menu"
        >
          <div class="py-1" role="none">
            <div v-for="column in allColumns" :key="column.field" class="px-4 py-2">
              <CCheckbox
                :label="column.headerName"
                :model-value="isColumnVisible(column.field)"
                @update:model-value="toggleColumn(column.field, $event)"
              />
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useTableSettingsStore } from '@/stores/tableSettings';
import { useI18n } from 'vue-i18n';
import { DataTableColumn } from './DataTable.vue';

const { t } = useI18n();
const tableSettings = useTableSettingsStore();

const props = defineProps({
  tableId: {
    type: String,
    required: true,
  },
  columns: {
    type: Array<DataTableColumn>,
    required: true,
  },
  showBookmarks: {
    type: Boolean,
    default: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(['columns-updated', 'bookmark-applied']);

const isColumnsOpen = ref(false);
const isBookmarksOpen = ref(false);
const visibleColumns = ref<string[]>([]);
const bookmarks = ref<Record<string, any>>({});

const allColumns = computed(() => props.columns);

// Initialize visible columns
onMounted(() => {
  loadTableState();
  loadBookmarks();
});

// Load table state from store
const loadTableState = () => {
  const state = tableSettings.getTableState(props.tableId);
  if (state.visibleColumns.length > 0) {
    visibleColumns.value = state.visibleColumns;
    emit('columns-updated', visibleColumns.value);
  } else {
    // Default to all columns visible
    visibleColumns.value = props.columns.map((col: DataTableColumn) => col.field);
  }
};

// Load bookmarks
const loadBookmarks = () => {
  bookmarks.value = tableSettings.getBookmarks(props.tableId);
};

// Toggle column visibility
const toggleColumn = (columnId: string, isVisible: boolean) => {
  if (isVisible) {
    if (!visibleColumns.value.includes(columnId)) {
      visibleColumns.value.push(columnId);
    }
  } else {
    visibleColumns.value = visibleColumns.value.filter(id => id !== columnId);
  }

  // Save to store
  tableSettings.setVisibleColumns(props.tableId, visibleColumns.value);
  emit('columns-updated', visibleColumns.value);
};

// Check if a column is visible
const isColumnVisible = (columnId: string) => {
  return visibleColumns.value.includes(columnId);
};

// Toggle dropdowns
const toggleColumnsDropdown = () => {
  isColumnsOpen.value = !isColumnsOpen.value;
  if (isColumnsOpen.value) {
    isBookmarksOpen.value = false;
  }
};

const toggleBookmarksDropdown = () => {
  isBookmarksOpen.value = !isBookmarksOpen.value;
  if (isBookmarksOpen.value) {
    isColumnsOpen.value = false;
    loadBookmarks();
  }
};

// Close dropdowns when clicking outside
const onClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement;
  if (!target.closest('.s-dropdown')) {
    isColumnsOpen.value = false;
    isBookmarksOpen.value = false;
  }
};

// Add click outside listener
onMounted(() => {
  document.addEventListener('click', onClickOutside);
});

// Clean up event listener
onUnmounted(() => {
  document.removeEventListener('click', onClickOutside);
});

// Save current view as a bookmark
const saveCurrentView = async () => {
  const bookmarkName = prompt(t('table.enter_bookmark_name'));
  if (bookmarkName) {
    const state = {
      visibleColumns: [...visibleColumns.value],
      filters: { ...props.filters },
    };
    tableSettings.saveBookmark(props.tableId, bookmarkName, state);
    loadBookmarks();
  }
};

// Apply a bookmark
const applyBookmark = (bookmarkName: string) => {
  const bookmark = tableSettings.applyBookmark(props.tableId, bookmarkName);
  if (bookmark) {
    visibleColumns.value = bookmark.visibleColumns;
    emit('columns-updated', bookmark.visibleColumns);
    emit('bookmark-applied', bookmark.filters);
    isBookmarksOpen.value = false;
  }
};

// Delete a bookmark
const deleteBookmark = (bookmarkName: string) => {
  if (confirm(t('table.confirm_delete_bookmark'))) {
    tableSettings.deleteBookmark(props.tableId, bookmarkName);
    loadBookmarks();
  }
};

// Watch for filter changes to update bookmarks
watch(
  () => props.filters,
  newFilters => {
    tableSettings.saveFilters(props.tableId, newFilters);
  },
  { deep: true }
);
</script>
