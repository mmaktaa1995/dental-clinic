<template>
    <div ref="autocomplete" class="relative w-full">
        <!-- Input field -->
        <div class="relative">
            <CTextField v-model="searchValue" :name :label :errors @input="onSearch" @focus="(showDropdown = true)" />
            <span class="absolute left-3 ltr:right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-500" :class="{ '!top-[24px]': hasError }" @click="toggleDropdown">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
                </svg>
            </span>
        </div>

        <!-- Dropdown -->
        <div v-if="showDropdown" class="absolute z-50 mt-2 w-full bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto" @scroll="onScroll">
            <!-- Items -->
            <ul>
                <li v-for="item in items" :key="item.id" class="px-4 py-2 cursor-pointer hover:bg-gray-300 hover:bg-opacity-25 text-right ltr:text-left text-gray-800" @click="selectItem(item)">
                    {{ item.name }}
                </li>
            </ul>
            <!-- Loading indicator -->
            <div v-if="loading" class="py-2 text-center text-gray-500">Loading...</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount, nextTick, computed } from "vue"

const selectedItem = defineModel<any>({ required: true })
const selectedItemObject = defineModel<any | undefined>("object", { required: false })

const props = defineProps({
    fetchItems: {
        type: Function,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    errors: {
        type: Object,
        required: false,
        default: () => {},
    },
})

const autocomplete = ref<HTMLElement | null>(null)
const searchQuery = ref("")
const displayValue = ref("") // For showing selected value
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
        if (response.entries.length > 0) {
            items.value.push(...(response.entries as []))
        }
        hasMore.value = response.entries.length > 0
        page.value += 1
    } catch (error) {
        console.error("Error fetching items:", error)
    } finally {
        loading.value = false
    }
}

const searchValue = computed({
    get() {
        return showDropdown.value ? searchQuery.value : displayValue.value
    },
    set(value: string) {
        searchQuery.value = value
    },
})

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
    showDropdown.value = false
    selectedItem.value = item.id
    selectedItemObject.value = item
    displayValue.value = item.name // Set the display value
    nextTick().then(() => {
        searchQuery.value = "" // Clear the search query
    })
}

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value
}

onMounted(() => {
    loadItems()
    if (selectedItemObject.value) {
        displayValue.value = selectedItemObject.value.name // Set the initial display value
    }
})

watch(selectedItemObject, () => {
    if (selectedItemObject.value) {
        displayValue.value = selectedItemObject.value.name // Update display value on selection
    }
})

watch(showDropdown, (value) => {
    if (!value) searchQuery.value = "" // Clear search when closing
})

const handleClickOutside = (event: MouseEvent) => {
    if (autocomplete.value && !autocomplete.value.contains(event.target as Node)) {
        showDropdown.value = false
    }
}

const hasError = computed(() => {
    return props.errors && props.errors[props.name]
})

onMounted(() => {
    document.addEventListener("click", handleClickOutside)
})

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside)
})
</script>

<style scoped>
/* Add any necessary custom styles here */
</style>
