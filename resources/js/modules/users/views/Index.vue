<template>
  <div class="space-y-4">
    <CDataTable :store="usersStore" :columns="columns" @row-clicked="openUserDetails">
      <template #header>
        <div class="flex justify-between items-center">
          <div class="font-semibold text-lg">{{ $t('users.moduleName') }}</div>
          <div class="flex gap-2">
            <ImportExportButtons
              model-type="users"
              :filters="exportFilters"
              @import-complete="handleImportComplete"
            />
            <CPermissionGuard permission="create-users">
              <CButton sm type="primary" :to="{ name: 'users-create' }">
                {{ $t('global.actions.create') }}</CButton
              >
            </CPermissionGuard>
          </div>
        </div>
      </template>
      <template #filters>
        <div class="grid grid-cols-2 gap-4 w-full">
          <CTextField
            v-model="usersStore.query"
            class="w-100"
            :label="$t('users.name')"
            name="name"
          ></CTextField>
          <CTextField
            v-model="usersStore.email"
            class="w-100"
            :label="$t('users.email')"
            name="email"
          ></CTextField>
        </div>
      </template>
    </CDataTable>
    <CDetailPageOutlet :reload-list="reload" />
  </div>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from '@/composables/entryListUpdater';
import { UserEntry, useUsersStore } from '@/modules/users/store';
import { useRouter } from 'vue-router';
import CDetailPageOutlet from '@/components/CDetailPage/CDetailPageOutlet.vue';
import { useI18n } from 'vue-i18n';
import { DataTableColumn } from '@/components/Table/DataTable.vue';
import DateTime from '@/components/Table/components/DateTime.vue';
import { useRouteQueryParam } from '@/logic/routeQuerySync';
import { storeToRefs } from 'pinia';
import CButton from '@/components/CButton.vue';
import ImportExportButtons from '@/components/ImportExportButtons.vue';
import { useToastStore } from '@/modules/global/toastStore';
import { computed } from 'vue';

// Initialize the users store
const usersStore = useUsersStore();
const router = useRouter();
const { t } = useI18n();
const { email } = storeToRefs(usersStore);
const toastStore = useToastStore();

// Computed property to provide current filters to export component
const exportFilters = computed(() => ({
  query: usersStore.query,
  email: usersStore.email,
}));

const handleImportComplete = (result: { success: boolean; message: string }) => {
  if (result.success) {
    toastStore.success(result.message);
    // Refresh the users after import
    reload();
  } else {
    toastStore.error(result.message);
  }
};

// Set up route query param syncing
useRouteQueryParam('email', undefined, 'string', { targetRef: email });

// Set up list updater
const { reload } = useEntryListUpdater('/users', usersStore);

// Define the columns for the data table
const columns: DataTableColumn[] = [
  { field: 'name', headerName: t('users.name') },
  { field: 'email', headerName: t('users.email') },
  { field: 'created_at', headerName: t('users.createdAt'), cellRenderer: DateTime },
];

// Function to open user details
const openUserDetails = (rowData: UserEntry) => {
  router.push({ name: 'users/general', params: { id: rowData.id } });
};
</script>
