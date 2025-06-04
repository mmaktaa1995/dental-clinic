<template>
  <div class="flex flex-wrap gap-2">
    <CPermissionGuard permission="manage-backups">
      <CButton
        sm
        type="info"
        @click="downloadBackup"
        :title="$t('backups.download')"
      >
        <i class="fas fa-download"></i>
      </CButton>
      <CButton
        sm
        type="warning"
        @click="confirmRestore"
        :loading="backupsStore.isRestoringBackup && backupsStore.restoringFilename === entry.filename"
        :title="$t('backups.restore')"
      >
        <i class="fas fa-undo"></i>
      </CButton>
      <CButton
        sm
        type="error"
        @click="confirmDelete"
        :title="$t('backups.delete')"
      >
        <i class="fas fa-trash"></i>
      </CButton>
    </CPermissionGuard>
  </div>
</template>

<script setup lang="ts">
import { DataTableColumn } from "@/components/Table/DataTable.vue";
import { BackupEntry, useBackupsStore } from "@/modules/backups/store";
import CButton from "@/components/CButton.vue";
import { useToastStore } from "@/modules/global/toastStore";
import { useConfirmStore } from "@/modules/global/confirmStore";
import { useI18n } from "vue-i18n";

const props = defineProps<{
  value: any;
  entry: BackupEntry;
  column: DataTableColumn;
}>();

const backupsStore = useBackupsStore();
const toastStore = useToastStore();
const confirmStore = useConfirmStore();
const { t } = useI18n();

// Function to download a backup
const downloadBackup = () => {
  window.open(backupsStore.getDownloadUrl(props.entry.filename), '_blank');
};

// Function to confirm and restore a backup
const confirmRestore = async () => {
  const confirmed = await confirmStore.confirm({
    title: t('backups.confirmRestoreTitle'),
    message: t('backups.confirmRestoreMessage'),
    confirmText: t('backups.restore'),
    cancelText: t('global.actions.cancel'),
    type: 'warning',
  });

  if (confirmed) {
    try {
      backupsStore.setRestoringFilename(props.entry.filename);
      const result = await backupsStore.restoreBackup(props.entry.filename);
      toastStore.success(result.message);
    } catch (error: any) {
      toastStore.error(error.response?.data?.message || t('backups.restoreError'));
    } finally {
      backupsStore.setRestoringFilename('');
    }
  }
};

// Function to confirm and delete a backup
const confirmDelete = async () => {
  const confirmed = await confirmStore.confirm({
    title: t('backups.confirmDeleteTitle'),
    message: t('backups.confirmDeleteMessage'),
    confirmText: t('global.actions.delete'),
    cancelText: t('global.actions.cancel'),
    type: 'danger',
  });

  if (confirmed) {
    try {
      const result = await backupsStore.deleteBackup(props.entry.filename);
      toastStore.success(result.message);
      // backupsStore.loadEntries();
    } catch (error: any) {
      toastStore.error(error.response?.data?.message || t('backups.deleteError'));
    }
  }
};
</script>
