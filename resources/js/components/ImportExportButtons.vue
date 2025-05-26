<template>
  <div class="flex gap-2">
    <ExportButton :model-type="modelType" :filters="filters" />

    <CButton v-if="hasImportPermission" type="info" sm @click="openImportModal">
      <c-icon class="ml-1 ltr:mr-1 ltr:m-l-0">fas fa-upload</c-icon>

      <span> {{ $t('import-export.import') }}</span>
    </CButton>

    <ImportExportModal
      v-model="isImportModalOpen"
      :model-type="modelType"
      :title="$t('import-export.importData') + ' - ' + $t(`import-export.models.${modelType}`)"
      @close="closeImportModal"
      @import-complete="handleImportComplete"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, defineProps, defineEmits } from 'vue';
import { useI18n } from 'vue-i18n';
import ExportButton from './ExportButton.vue';
import ImportExportModal from './ImportExportModal.vue';
import { hasPermission } from '@/utils/permissions';
import CButton from './CButton.vue';

type ModelType =
  | 'patients'
  | 'services'
  | 'expenses'
  | 'users'
  | 'appointments'
  | 'payments'
  | 'debits'
  | 'visits';

interface ImportResult {
  success: boolean;
  message: string;
  processed: number;
  failed: number;
}

const props = defineProps<{
  modelType: ModelType;
  filters?: Record<string, any>;
  color?: string;
}>();

const isImportModalOpen = ref(false);

const emit = defineEmits<{
  (e: 'import-complete', result: ImportResult): void;
}>();

const { t } = useI18n();

// Check if user has permission to import this model type
const hasImportPermission = computed(() => {
  const permissionName = `import-${props.modelType}`;
  return hasPermission(permissionName);
});

function openImportModal(): void {
  isImportModalOpen.value = true;
}

function closeImportModal(): void {
  isImportModalOpen.value = false;
}

function handleImportComplete(result: ImportResult): void {
  emit('import-complete', result);
  // Auto-close modal after successful import
  if (result.success) {
    setTimeout(() => {
      closeImportModal();
    }, 2000);
  }
}
</script>
