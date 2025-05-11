<template>
    <template v-if="loaded">
        <CSidebar v-if="accountStore.user.id"></CSidebar>
        <div class="w-full">
            <CNavbar v-if="accountStore.user.id"></CNavbar>
            <router-view></router-view>
        </div>
    </template>
    <div class="modal-teleport"></div>
</template>
<script setup lang="ts">
import CNavbar from "@/layout/CNavbar.vue"
import CSidebar from "@/layout/CSidebar.vue"
import { onBeforeMount, ref } from "vue"
import { useAccountStore } from "@/modules/auth/accountStore"
import { getSelectedLanguage, setHtmlLangAttributes, setI18n } from "@/logic/i18n"
import { useI18n } from "vue-i18n"
import { useSettingsStore } from "@/modules/global/settingsStore"

setI18n(useI18n<any, ReturnType<typeof getSelectedLanguage>["value"]>())

const loaded = ref(false)
const accountStore = useAccountStore()
const settingsStore = useSettingsStore()

onBeforeMount(async () => {
    try {
        await accountStore.getUser()
        await settingsStore.getExchangeRate()
        await settingsStore.getLastFileNumber()
        await settingsStore.getTeeth()
        await setHtmlLangAttributes()
    } catch (e) {
        console.log(e)
    }
    loaded.value = true
})
</script>
