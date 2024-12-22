<template>
    <ul>
        <li v-for="(subPage, subPageKey) in visibleSubPages" :key="subPageKey" :title="subPage.title" :append-icon="subPage.isDirty ? 'far fa-pencil' : ''">
            <router-link :to="store.subPageLinks[subPageKey]" active-class="s-k-detail-menu-item-active" class="s-k-detail-menu-item text-base text-gray-900 transition block p-2">
                {{ subPage.title }}
            </router-link>
        </li>
    </ul>
</template>

<script setup lang="ts">
import { DetailPageStore, SubPage } from "@/store/factories/detailPageStore"
import { computed, unref } from "vue"

const props = defineProps<{
    store: DetailPageStore
}>()

const visibleSubPages = computed(() => {
    const result: Record<string, SubPage> = {}
    for (const [key, subPage] of Object.entries(props.store.subPages) as [string, SubPage][]) {
        let isVisible = true
        if (typeof subPage.visible === "function") {
            isVisible = unref(subPage.visible(props.store)) as boolean
        }
        if (isVisible) {
            result[key] = subPage
        }
    }
    return result
})
</script>

<style scoped lang="scss">
.s-k-detail-menu-item-active,
.s-k-detail-menu-item:hover {
    @apply text-teal-600 bg-gray-200 bg-opacity-50;
    border-radius: 0;
}
:global(.s-k-detail-menu-item-active .v-list-item__overlay),
:global(.s-k-detail-menu-item:hover .v-list-item__overlay) {
    opacity: 0;
}
</style>
