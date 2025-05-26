<template>
  <div class="relative inline-block text-left" v-if="hasExportPermission">
    <div>
      <CButton type="accent" sm class="flex items-center justify-center" @click="toggleDropdown">
        <c-icon class="ml-1 ltr:mr-1 ltr:m-l-0">fas fa-download</c-icon>
        {{ $t('import-export.export') }}
      </CButton>
    </div>

    <div
      v-if="isOpen"
      class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
    >
      <div class="py-1" role="menu">
        <a
          href="#"
          class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
          role="menuitem"
          @click.prevent="exportData('xlsx')"
        >
          <svg
            class="mr-3 h-5 w-5 text-green-500"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            />
          </svg>
          {{ $t('import-export.exportFormats.excel') }}
        </a>
        <a
          href="#"
          class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
          role="menuitem"
          @click.prevent="exportData('csv')"
        >
          <svg
            class="mr-3 h-5 w-5 text-blue-500"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            />
          </svg>
          {{ $t('import-export.exportFormats.csv') }}
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { hasPermission } from '@/utils/permissions';
import CButton from './CButton.vue';
import { api } from '@/logic/api';
import { useToastStore } from '@/modules/global/toastStore';

type ModelType =
  | 'patients'
  | 'services'
  | 'expenses'
  | 'users'
  | 'appointments'
  | 'payments'
  | 'debits'
  | 'visits';

interface Props {
  modelType: ModelType;
  filters?: Record<string, any>;
  color?: string;
}

const props = withDefaults(defineProps<Props>(), {
  color: 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
});

const isOpen = ref(false);
const { t } = useI18n();

// Check if user has permission to export this model type
const hasExportPermission = computed(() => {
  const permissionName = `export-${props.modelType}`;
  return hasPermission(permissionName);
});

function toggleDropdown(): void {
  isOpen.value = !isOpen.value;
}

function closeDropdown(event: MouseEvent): void {
  // Close dropdown when clicking outside
  const target = event.target as HTMLElement;
  if (!target.closest('.relative')) {
    isOpen.value = false;
  }
}

async function exportData(format: string): Promise<void> {
  isOpen.value = false;

  try {
    const params: Record<string, any> = { format };

    // Add filters to params if they exist
    if (props.filters && Object.keys(props.filters).length > 0) {
      Object.entries(props.filters).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
          params[`filters[${key}]`] = String(value);
        }
      });
    }

    // Create a temporary link to trigger the download
    const link = document.createElement('a');
    link.style.display = 'none';
    document.body.appendChild(link);


    try {
      // Use the API client to get the file with authentication
      const response = await api.get(`/export/${props.modelType}`, {
        params,
        responseType: 'blob',
      });

      // Create a blob from the response
      const blob = new Blob([response], { type: response.type });
      const url = window.URL.createObjectURL(blob);

      // Set up the download link
      link.href = url;
      link.setAttribute('download', `${props.modelType}-export.${format}`);

      // Trigger the download
      link.click();

      // Clean up
      window.URL.revokeObjectURL(url);
    } finally {
      document.body.removeChild(link);
    }
  } catch (error) {
    console.error('Export failed:', error);
    const toastStore = useToastStore();
    toastStore.error('Failed to export data. Please try again.');
  }
}

// Add and remove event listeners for closing dropdown when clicking outside
onMounted(() => {
  document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown);
});
</script>

<style scoped>
.dropdown-item {
  cursor: pointer;
}
</style>
