<template>
    <CSidebar></CSidebar>
    <div class="w-full">
        <CNavbar></CNavbar>
        <router-view></router-view>
    </div>
</template>
<script setup lang="ts">
import CNavbar from "@/layout/CNavbar.vue"
import CSidebar from "@/layout/CSidebar.vue"
import { onBeforeMount } from "vue"
import { useAccountStore } from "@/stores/accountStore"

const accountStore = useAccountStore()
onBeforeMount(() => {
    accountStore.getUser()
})
</script>
