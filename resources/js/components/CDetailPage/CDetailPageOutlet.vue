<template>
    <router-view v-slot="{ Component }">
        <transition name="fade">
            <component :is="Component" v-if="route.meta.isDetailPage" :reload-list="reloadList" />
        </transition>
    </router-view>
</template>
<script setup lang="ts">
import { useRoute } from "vue-router"

defineProps<{
    reloadList?: () => Promise<void> | undefined
}>()
const route = useRoute()
</script>
