<template>
    <router-view v-slot="{ Component }">
        <Transition name="drawer-slide-in">
            <component :is="Component" v-if="route.meta.isDetailPage" :reload-list="reloadList" />
        </Transition>
    </router-view>
</template>
<script setup lang="ts">
import { useRoute } from "vue-router"

defineProps<{
    reloadList?: () => Promise<void> | undefined
}>()
const route = useRoute()
</script>
