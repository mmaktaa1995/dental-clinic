<template>
    <ul>
        <li v-for="(subPage, subPageKey) in visibleSubPages" :key="subPageKey" :title="subPage.title" class="">
            <router-link :to="store.subPageLinks[subPageKey]" active-class="s-k-detail-menu-item-active" class="s-k-detail-menu-item text-base text-gray-700 transition duration-100 flex items-center justify-between py-2 px-3">
                <span>{{ subPage.title }}</span>
                <c-icon v-if="subPage.isDirty">fas fa-pencil</c-icon>
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
    @apply text-cyan-700 bg-gray-300 bg-opacity-50;
    border-radius: 0;
}
</style>
