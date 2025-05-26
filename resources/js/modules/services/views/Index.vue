<template>
  <div class="space-y-4">
    <CDataTable :columns="columns" :store="servicesStore" @row-clicked="rowClicked">
      <template #header>
        <div class="flex justify-between items-center">
          <div class="font-semibold text-lg">{{ $t('services.title') }}</div>
          <div class="flex gap-2">
            <ImportExportButtons
              model-type="services"
              :filters="exportFilters"
              @import-complete="handleImportComplete"
            />
            <div class="w-1 h-full bg-gray-200"></div>
            <CButton sm type="primary" :to="{ name: `services/general`, params: { id: -1 } }">
              {{ $t('global.actions.create') }}
            </CButton>
          </div>
        </div>
      </template>
      <template #filters>
        <div class="grid grid-cols-2 gap-4 w-full">
          <CTextField
            v-model="servicesStore.query"
            class="w-100"
            :label="$t('services.name')"
            name="name"
          ></CTextField>
        </div>
      </template>
    </CDataTable>
    <CDetailPageOutlet :reload-list="reload" />
  </div>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from '@/composables/entryListUpdater';
import { useI18n } from 'vue-i18n';
import { formatNumber } from '@/logic/helpers.js';
import { useRouter } from 'vue-router';
import DateTime from '@/components/Table/components/DateTime.vue';
import { DataTableColumn } from '@/components/Table/DataTable.vue';
import { useServicesStore } from '@/modules/services/store';
import CDetailPageOutlet from '@/components/CDetailPage/CDetailPageOutlet.vue';
import { useSettingsStore } from '@/modules/global/settingsStore';
import { useToastStore } from '@/modules/global/toastStore';
import { computed } from 'vue';
import ImportExportButtons from '@/components/ImportExportButtons.vue';

const servicesStore = useServicesStore();
const settingsStore = useSettingsStore();
const router = useRouter();
const { t } = useI18n();
const toastStore = useToastStore();

// Computed property to provide current filters to export component
const exportFilters = computed(() => ({
  query: servicesStore.query,
}));

const handleImportComplete = (result: { success: boolean; message: string }) => {
  if (result.success) {
    toastStore.success(result.message);
    // Refresh the services after import
    reload();
  } else {
    toastStore.error(result.message);
  }
};

const columns: DataTableColumn[] = [
  { field: 'name', headerName: t('services.name') },
  {
    field: 'price',
    headerName: t('services.price') + ` (${t('global.currencies.SYP')})`,
    valueFormatter: (value: any) => {
      return formatNumber(value);
    },
  },
  {
    field: 'price',
    headerName: t('services.price') + ` (${t('global.currencies.USD')})`,
    valueFormatter: (value: any) => {
      return formatNumber(value / settingsStore.exchangeRate.usd_aleppo) + '$';
    },
  },
  {
    field: 'created_at',
    headerName: t('payments.date'),
    cellRenderer: DateTime,
  },
];

const rowClicked = (row: any) => {
  router.push({ name: 'services/general', params: { id: row.id } });
};

const { reload } = useEntryListUpdater(`/services`, servicesStore);
</script>
