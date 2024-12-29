<template>
    <template v-if="loaded">
        <CSidebar></CSidebar>
        <div class="w-full">
            <CNavbar></CNavbar>
            <router-view></router-view>
        </div>
        <div class="modal-teleport"></div>
    </template>
</template>
<script setup lang="ts">
import CNavbar from "@/layout/CNavbar.vue"
import CSidebar from "@/layout/CSidebar.vue"
import { onBeforeMount, ref } from "vue"
import { useAccountStore } from "@/modules/auth/accountStore"
import { getSelectedLanguage, setI18n } from "@/logic/i18n"
import { useI18n } from "vue-i18n"
import { useSettingsStore } from "@/modules/account/settingsStore"

setI18n(useI18n<any, ReturnType<typeof getSelectedLanguage>["value"]>())

const loaded = ref(false)
const accountStore = useAccountStore()
const settingsStore = useSettingsStore()

onBeforeMount(async () => {
    await accountStore.getUser()
    await settingsStore.getExchangeRate()
    await settingsStore.getLastFileNumber()
    loaded.value = true
})
</script>
