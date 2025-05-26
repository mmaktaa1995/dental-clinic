<template>
  <CDialog v-model="isOpen" @close="closeModal" @confirmCallback="importFile" size="md">
    <template #header>
      {{ title }}
    </template>
    <template #body>
      <div class="p-4">
        <div
          class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-all duration-300"
          @dragover.prevent="onDragOver"
          @dragleave.prevent="onDragLeave"
          @drop.prevent="onDrop"
          :class="{ 'border-indigo-500 bg-indigo-50': isDragging }"
        >
          <div v-if="!selectedFile" class="flex flex-col items-center">
            <svg
              class="w-12 h-12 text-gray-400 mb-4"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
              />
            </svg>
            <p class="mb-2">{{ $t('import-export.dragDropHint') }}</p>
            <label
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer"
            >
              {{ $t('import-export.browseFiles') }}
              <input type="file" class="hidden" accept=".xlsx,.xls,.csv" @change="onFileSelected" />
            </label>
            <p class="text-sm text-gray-500 mt-2">{{ $t('import-export.supportedFormats') }}</p>
          </div>
          <div v-else class="flex flex-col items-center">
            <svg
              class="w-12 h-12 text-green-500 mb-4"
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
            <p class="mb-4 font-medium">{{ selectedFile.name }}</p>
            <div class="flex space-x-2">
              <button
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                @click="removeFile"
              >
                <svg
                  class="-ml-0.5 mr-2 h-4 w-4"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
                {{ $t('import-export.removeFile') }}
              </button>
              <button
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                @click="importFile"
              >
                <svg
                  class="-ml-0.5 mr-2 h-4 w-4"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                  />
                </svg>
                {{ $t('import-export.importFile') }}
              </button>
            </div>
          </div>
        </div>

        <div class="mt-6 text-center">
          <h3 class="text-sm font-medium text-gray-900">{{ $t('import-export.needTemplate') }}</h3>
          <p class="text-sm text-gray-500">{{ $t('import-export.templateDescription') }}</p>
          <button
            class="mt-2 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            @click="downloadTemplate"
          >
            <svg
              class="-ml-1 mr-2 h-5 w-5 text-gray-500"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
              />
            </svg>
            {{ $t('import-export.downloadTemplate') }}
          </button>
        </div>

        <div v-if="importStatus" class="mt-6">
          <div
            :class="{
              'rounded-md p-4': true,
              'bg-green-50 text-green-800': importStatus.success,
              'bg-red-50 text-red-800': !importStatus.success,
            }"
          >
            <div class="flex">
              <div class="flex-shrink-0">
                <svg
                  v-if="importStatus.success"
                  class="h-5 w-5 text-green-400"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                  />
                </svg>
                <svg
                  v-else
                  class="h-5 w-5 text-red-400"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium">{{ importStatus.message }}</h3>
                <p v-if="importStatus.success" class="mt-1 text-sm">
                  {{ $t('import-export.processed') }}: {{ importStatus.processed }} |
                  {{ $t('import-export.failed') }}: {{ importStatus.failed }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </CDialog>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';
import CDialog from './Dialog.vue';
import { api } from '@/logic/api';

type ModelType = 'patients' | 'services' | 'expenses' | 'users' | 'appointments';

interface ImportStatus {
  success: boolean;
  message: string;
  processed: number;
  failed: number;
  loading?: boolean;
}

interface Props {
  modelType: ModelType;
  title: string;
}

const isOpen = defineModel<boolean>();

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'close'): void;
  (e: 'import-complete', result: ImportStatus): void;
}>();

const { t } = useI18n();
const isDragging = ref(false);
const selectedFile = ref<File | null>(null);
const importStatus = ref<ImportStatus | null>(null);

const apiEndpoint = computed((): string => {
  return `/api/import/${props.modelType}`;
});

const templateEndpoint = computed((): string => {
  return `/api/v1/export/${props.modelType}?format=xlsx&template=true`;
});

function onDragOver(): void {
  isDragging.value = true;
}

function onDragLeave(): void {
  isDragging.value = false;
}

function onDrop(event: DragEvent): void {
  isDragging.value = false;
  const files = event.dataTransfer?.files;
  if (files && files.length > 0) {
    const file = files[0];
    if (isValidFileType(file)) {
      selectedFile.value = file;
    } else {
      alert(t('import-export.invalidFileFormat'));
    }
  }
}

function onFileSelected(event: Event): void {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];
  if (file && isValidFileType(file)) {
    selectedFile.value = file;
  } else if (file) {
    alert(t('import-export.invalidFileFormat'));
  }
}

function isValidFileType(file: File): boolean {
  const validTypes = [
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/csv',
  ];
  return (
    validTypes.includes(file.type) ||
    file.name.endsWith('.xlsx') ||
    file.name.endsWith('.xls') ||
    file.name.endsWith('.csv')
  );
}

function removeFile(): void {
  selectedFile.value = null;
  importStatus.value = null;
}

async function importFile(): Promise<void> {
  if (!selectedFile.value) return;

  const formData = new FormData();
  formData.append('file', selectedFile.value);

  try {
    importStatus.value = {
      success: false,
      message: t('import-export.importing'),
      processed: 0,
      failed: 0,
      loading: true,
    };
    const response = await api.post(apiEndpoint.value, formData, null, {
        'Content-Type': 'multipart/form-data',
    });

    importStatus.value = response.data;

    if (response.data.success) {
      emit('import-complete', response.data);
    }
  } catch (error: any) {
    importStatus.value = {
      success: false,
      message: error.response?.data?.message || t('import-export.importError'),
      processed: 0,
      failed: 0,
    };
  }
}

function downloadTemplate(): void {
  window.open(templateEndpoint.value, '_blank');
}

function closeModal(): void {
  emit('close');
  // Reset state when modal is closed
  setTimeout(() => {
    selectedFile.value = null;
    importStatus.value = null;
    isDragging.value = false;
  }, 300);
}
</script>

<style scoped>
.import-export-container {
  padding: 1rem;
}

.upload-area {
  border: 2px dashed #ccc;
  border-radius: 8px;
  padding: 2rem;
  text-align: center;
  transition: all 0.3s ease;
}

.drag-over {
  border-color: #4f46e5;
  background-color: rgba(79, 70, 229, 0.05);
}

.upload-placeholder i,
.selected-file i {
  font-size: 3rem;
  color: #6c757d;
  margin-bottom: 1rem;
}

.selected-file i {
  color: #198754;
}

.template-section {
  text-align: center;
}

.import-status {
  margin-top: 1rem;
}
</style>
