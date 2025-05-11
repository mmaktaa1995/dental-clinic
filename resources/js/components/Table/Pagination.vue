<template>
    <div class="flex justify-between items-center bg-white py-2 px-3">
        <!-- eslint-disable vue/no-mutating-props -->

        <div class="flex items-center gap-4">
            <nav class="flex justify-center items-center gap-x-1" aria-label="Pagination">
                <button
                    type="button"
                    :disabled="store.pagination.page === 1"
                    class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-md text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none disabled:cursor-not-allowed"
                    aria-label="Previous"
                    @click="prev"
                >
                    <svg class="shrink-0 size-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                    <span class="sr-only">Previous</span>
                </button>
                <div class="flex items-center gap-x-1">
                    <span class="min-h-[38px] min-w-[38px] flex justify-center items-center border border-gray-200 text-gray-800 py-2 px-3 text-sm rounded-md focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        {{ store.pagination.page }}
                    </span>
                    <span class="min-h-[38px] flex justify-center items-center text-gray-500 py-2 px-1.5 text-sm">{{ $t("global.pagination.of") }}</span>
                    <span class="min-h-[38px] flex justify-center items-center text-gray-800 py-2 px-1.5 text-base">
                        {{ store.pagination.last_page }}
                    </span>
                </div>
                <button
                    :disabled="store.pagination.page === store.pagination.last_page"
                    type="button"
                    class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-md text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none disabled:cursor-not-allowed"
                    aria-label="Next"
                    @click="next"
                >
                    <span class="sr-only">Next</span>
                    <svg class="shrink-0 size-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>
            </nav>
            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500 flex-shrink-0">{{ $t("global.pagination.page") }}</div>
                <CSelect v-model="store.pagination.page" name="per_page" :options="pageOptions"></CSelect>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-sm text-gray-500 flex-shrink-0">{{ $t("global.pagination.entriesPerPage") }}</div>
            <CSelect v-model="store.pagination.per_page" name="per_page" :options="perPageOptions"></CSelect>
        </div>
    </div>
</template>
<script setup lang="ts">
import { EntryListStore } from "@/store/factories/entryListStore"
import { computed } from "vue"

const props = defineProps<{
    store: EntryListStore
}>()

const pageOptions = computed(() => {
    return Array.from(Array(props.store.pagination.last_page).keys()).map((i) => ({ id: i + 1, label: i + 1 }))
})

const perPageOptions = [
    { id: 10, label: 10 },
    { id: 20, label: 20 },
    { id: 50, label: 50 },
    { id: 100, label: 100 },
]

const next = () => {
    if (props.store.pagination.page === props.store.pagination.last_page) {
        return
    }
    props.store.pagination.page++
}

const prev = () => {
    if (props.store.pagination.page === 1) {
        return
    }
    props.store.pagination.page--
}
</script>
