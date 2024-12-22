<template>
    <div v-if="showSide" class="flex flex-shrink-0 sidebar" :class="{ active: layoutStore.showSidebar }">
        <div class="flex flex-col w-64">
            <div class="flex flex-col h-0 flex-1">
                <!-- Logo -->
                <div class="flex items-center justify-center px-4 bg-gray-800 text-xl text-white font-medium">
                    <img src="/images/long-logo.png" alt="logo" style="filter: brightness(11.5) drop-shadow(2px 1px 5px #000)" />
                </div>

                <div class="flex-1 flex flex-col overflow-y-auto bg-gray-800">
                    <!-- Logs tabs -->
                    <h3 class="px-3 mt-8 text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">النظام</h3>

                    <nav v-if="accountStore.user!.admin" class="px-2 py-4 bg-gray-800">
                        <router-link
                            v-for="link in links"
                            :key="link.name"
                            :to="{ name: link.name }"
                            exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5 text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <component :is="link.icon" size="6" class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></component>
                            {{ link.text }}
                        </router-link>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { computed } from "vue"
import { useAccountStore } from "@/modules/auth/accountStore"
import { useRoute } from "vue-router"
import { useLayoutStore } from "@/store/layoutStore"
import { useI18n } from "vue-i18n"

const accountStore = useAccountStore()
const layoutStore = useLayoutStore()
const route = useRoute()
const { t } = useI18n()

const showSide = computed(() => {
    return accountStore.user && !route.path.includes("unauthorized") && !route.path.includes("404")
})

const links = [
    {
        name: "statistics",
        text: t("sideBar.menu.statistics"),
        icon: "CIconChartBar",
    },
    {
        name: "patients-index",
        text: t("sideBar.menu.patients"),
        icon: "CIconUsers",
    },
    {
        name: "patients-files-index",
        text: t("sideBar.menu.patientsFiles"),
        icon: "CIconFile",
    },
    {
        name: "payments-index",
        text: t("sideBar.menu.payments"),
        icon: "CIconMoney",
    },
    {
        name: "debits-index",
        text: t("sideBar.menu.debits"),
        icon: "CIconDebit",
    },
    {
        name: "expenses-index",
        text: t("sideBar.menu.expenses"),
        icon: "CIconExpenses",
    },
    {
        name: "appointments-index",
        text: t("sideBar.menu.appointments"),
        icon: "CIconCalendar",
    },
    {
        name: "services-index",
        text: t("sideBar.menu.services"),
        icon: "CIconCollection",
    },
]
</script>
