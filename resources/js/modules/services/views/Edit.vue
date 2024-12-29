<template>
    <c-container>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-700">{{ $t("services.serviceInfo") }}</h3>
            <p class="text-sm text-gray-500">{{ $t("services.serviceInfoDescription") }}</p>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <CTextField v-model="serviceDetailsStore.entry.name" :label="$t('services.name')" type="text" :errors="serviceDetailsStore.errors" name="name"></CTextField>
            <CTextField v-model="serviceDetailsStore.entry.price" :label="$t('services.price') + ` (${$t('global.currencies.SYP')})`" type="number" :errors="serviceDetailsStore.errors" name="price"></CTextField>
            <CTextField v-model="priceInUsd" :label="$t('services.price') + ` (${$t('global.currencies.USD')})`" disabled type="number" name="price_usd"></CTextField>
        </div>
    </c-container>
</template>

<script setup>
import { useServiceDetailsStore } from "@/modules/services/detailStore"
import { computed } from "vue"
import { useSettingsStore } from "@/modules/account/settingsStore"

const serviceDetailsStore = useServiceDetailsStore()
const settingsStore = useSettingsStore()

const priceInUsd = computed(() => {
    if (!serviceDetailsStore.entry.price) {
        return null
    }
    return (serviceDetailsStore.entry.price / settingsStore.exchangeRate.usd_aleppo).toFixed(2)
})
</script>
