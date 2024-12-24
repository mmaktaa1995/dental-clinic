<template>
    <div class="relative w-full">
        <!-- Input field -->
        <div class="relative">
            <input v-model="searchQuery" type="text" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Search..." @input="onSearch" @focus="(showDropdown = true)" />
            <span class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-500" @click="toggleDropdown">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
                </svg>
            </span>
        </div>

        <!-- Dropdown -->
        <div v-if="showDropdown" class="absolute z-10 mt-2 w-full bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto" @scroll="onScroll">
            <!-- Items -->
            <ul>
                <li v-for="item in items" :key="item.id" class="px-4 py-2 cursor-pointer hover:bg-teal-100" @click="selectItem(item)">
                    {{ item.name }}
                </li>
            </ul>
            <!-- Loading indicator -->
            <div v-if="loading" class="py-2 text-center text-gray-500">Loading...</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from "vue"

const props = defineProps({
    fetchItems: {
        type: Function,
        required: true,
    },
})

const searchQuery = ref("")
const items = ref([])
const showDropdown = ref(false)
const loading = ref(false)
const page = ref(1)
const hasMore = ref(true)

// Fetch initial items
const loadItems = async (reset = false) => {
    if (loading.value || !hasMore.value) return
    loading.value = true

    try {
        const response = await props.fetchItems(page.value, searchQuery.value)
        if (reset) items.value = []
        if (response.data.length > 0) {
            items.value.push(...response.data)
        }
        hasMore.value = response.data.length > 0
        page.value += 1
    } catch (error) {
        console.error("Error fetching items:", error)
    } finally {
        loading.value = false
    }
}

// Handle scrolling
const onScroll = (event: Event) => {
    const element = event.target as HTMLElement
    if (element.scrollTop + element.clientHeight >= element.scrollHeight) {
        loadItems()
    }
}

// Handle search
const onSearch = () => {
    page.value = 1
    hasMore.value = true
    loadItems(true)
}

// Handle item selection
const selectItem = (item: any) => {
    console.log("Selected item:", item)
    showDropdown.value = false
}

// Toggle dropdown visibility
const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value
}

onMounted(() => {
    loadItems()
})

watch(showDropdown, (value) => {
    if (!value) searchQuery.value = "" // Clear search when closing
})
</script>

<style scoped>
/* Add any necessary custom styles here */
</style>
