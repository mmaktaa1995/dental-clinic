<template>
  <div class="space-y-4">
    <CDataTable :store="backupsStore" :columns="columns">
      <template #header>
        <div class="flex justify-between items-center">
          <div class="font-semibold text-lg">{{ $t('backups.moduleName') }}</div>
          <div class="flex gap-2">
            <CPermissionGuard permission="manage-backups">
              <CButton 
                sm 
                type="primary" 
                @click="createBackup"
                :loading="backupsStore.isCreatingBackup"
              >
                {{ $t('backups.createBackup') }}
              </CButton>
            </CPermissionGuard>
          </div>
        </div>
      </template>
    </CDataTable>
  </div>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from '@/composables/entryListUpdater';
import { useBackupsStore } from '@/modules/backups/store';
import { useI18n } from 'vue-i18n';
import { DataTableColumn } from '@/components/Table/DataTable.vue';
import DateTime from '@/components/Table/components/DateTime.vue';
import CButton from '@/components/CButton.vue';
import { useToastStore } from '@/modules/global/toastStore';
import BackupActions from '@/modules/backups/components/table/BackupActions.vue';

// Initialize the backups store
const backupsStore = useBackupsStore();
const { t } = useI18n();
const toastStore = useToastStore();

// Set up list updater
const { reload } = useEntryListUpdater('/backups', backupsStore);

// Define the columns for the data table
const columns: DataTableColumn[] = [
  { field: 'filename', headerName: t('backups.filename') },
  { field: 'size', headerName: t('backups.size') },
  { field: 'created_at', headerName: t('backups.createdAt'), cellRenderer: DateTime },
  { field: 'actions', headerName: t('global.actions.label'), cellRenderer: BackupActions },
];

// Function to create a new backup
const createBackup = async () => {
  try {
    const result = await backupsStore.createBackup();
    toastStore.success(result.message);
    reload();
  } catch (error: any) {
    toastStore.error(error.response?.data?.message || t('backups.createError'));
  }
};
</script>
