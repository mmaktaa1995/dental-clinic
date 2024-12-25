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

<style scoped>
.drawer-slide-in-enter-active,
.drawer-slide-in-leave-active {
    transform: translateX(0) !important;
}

.drawer-slide-in-enter-from,
.drawer-slide-in-leave-to {
    .v-navigation-drawer {
        transform: translateX(100%) !important;
    }
}
</style>
