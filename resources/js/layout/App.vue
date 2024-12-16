<template>
    <CNavbar></CNavbar>
    <div class="w-full flex">
        <CSidebar></CSidebar>
        <router-view></router-view>
    </div>
</template>
<script setup lang="ts">
import CNavbar from "@/layout/CNavbar.vue"
import CSidebar from "@/layout/CSidebar.vue"
</script>
