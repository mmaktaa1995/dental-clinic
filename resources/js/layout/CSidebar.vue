<template>
  <div
    v-if="showSide"
    class="flex flex-shrink-0 sidebar bg-gray-800"
    :class="{ active: layoutStore.showSidebar }"
  >
    <div class="flex flex-col w-64">
      <div class="flex flex-col h-0 flex-1">
        <!-- Logo -->
        <div class="flex items-center justify-center px-4 text-xl text-white font-medium">
          <img
            src="/images/long-logo.png"
            alt="logo"
            style="filter: brightness(11.5) drop-shadow(2px 1px 5px #000)"
          />
        </div>

        <div v-if="accountStore.user" class="flex-1 flex flex-col overflow-y-auto mt-8">
          <template v-for="group in groupLinks" :key="group.group">
            <h3 class="px-3 text-xs leading-4 font-semibold text-gray-400 uppercase tracking-wider">
              {{ group.group }}
            </h3>

            <nav class="px-2 mb-4 bg-gray-800">
              <template v-for="link in group.links" :key="link.name">
                <router-link
                  v-if="!link.permission || hasPermission(link.permission)"
                  :to="{ name: link.name }"
                  exact
                  active-class="active-route"
                  class="route"
                >
                  <c-icon class="ml-3 ltr:ml-0 ltr:mr-3">fa-lg fas {{ link.icon }}</c-icon>
                  {{ link.text }}
                </router-link>
              </template>
            </nav>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { computed } from 'vue';
import { useAccountStore } from '@/modules/auth/accountStore';
import { useRoute } from 'vue-router';
import { useLayoutStore } from '@/store/layoutStore';
import { useI18n } from 'vue-i18n';
import { hasPermission } from '@/utils/permissions';

const accountStore = useAccountStore();
const layoutStore = useLayoutStore();
const route = useRoute();
const { t } = useI18n();

const showSide = computed(() => {
  return accountStore.user && !route.path.includes('unauthorized') && !route.path.includes('404');
});



const groupLinks = [
  {
    group: t('global.general'),
    links: [
      {
        name: 'statistics',
        text: t('sideBar.menu.statistics'),
        icon: 'fa-chart-line',
        permission: 'view-statistics',
      },
      {
        name: 'patients-index',
        text: t('sideBar.menu.patients'),
        icon: 'fa-users',
        permission: 'view-patient-records',
      },
      {
        name: 'appointments-index',
        text: t('sideBar.menu.appointments'),
        icon: 'fa-calendar-alt',
        permission: 'view-all-appointments',
      },
      {
        name: 'services-index',
        text: t('sideBar.menu.services'),
        icon: 'fa-layer-group',
        permission: 'view-services',
      },
    ],
  },
  {
    group: t('global.financial'),
    links: [
      {
        name: 'payments-index',
        text: t('sideBar.menu.payments'),
        icon: 'fa-wallet',
        permission: 'view-payments',
      },
      {
        name: 'debits-index',
        text: t('sideBar.menu.debits'),
        icon: 'fa-receipt',
        permission: 'view-debits',
      },
      {
        name: 'expenses-index',
        text: t('sideBar.menu.expenses'),
        icon: 'fa-hand-holding-usd',
        permission: 'view-expenses',
      },
    ],
  },
  {
    group: t('global.administration'),
    links: [
      {
        name: 'users-index',
        text: t('sideBar.menu.users'),
        icon: 'fa-user-shield',
        permission: 'view-users',
      },
      {
        name: 'roles-index',
        text: t('sideBar.menu.roles'),
        icon: 'fa-user-tag',
        permission: 'view-roles',
      },
      {
        name: 'backups',
        text: t('sideBar.menu.backups'),
        icon: 'fa-database',
        permission: 'manage-backups',
      },
    ],
  },
];
</script>
