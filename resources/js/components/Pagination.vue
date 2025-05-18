<template>
    <div>
        <ul v-if="pages" class="list-reset">
            <li class="inline-block bg-white hover:bg-blue-lightest border mr-1">
                <a href="#" class="no-underline text-grey-darker block py-3 px-4" :class="{ 'bg-grey-lightest text-grey cursor-not-allowed': currentPage === 1 }" @click.prevent="getPreviousPage">Previous</a>
            </li>
            <li v-for="(page, index) in range" :key="index" class="inline-block bg-white hover:bg-blue-lightest border-t border-b border-l" :class="{ 'border-r': index === range.length - 1 }">
                <a v-if="page !== '...'" href="#" class="no-underline text-grey-darker block py-3 px-4" :class="{ 'bg-blue-lighter': page === currentPage }" @click.prevent="getPage(page)">
                    {{ page }}
                </a>
                <a v-else href="#" class="no-underline text-grey-darker block py-3 px-4">
                    {{ page }}
                </a>
            </li>
            <li class="inline-block bg-white hover:bg-blue-lightest border ml-1">
                <a href="#" class="no-underline text-grey-darker block py-3 px-4" :class="{ 'bg-grey-lightest text-grey cursor-not-allowed': currentPage >= pages }" @click.prevent="getNextPage">Next</a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue"

// Props
const props = defineProps({
    pages: {
        type: Number,
        default: 0,
    },
    currentPage: {
        type: Number,
        default: 1,
    },
})

const emit = defineEmits(["page-changed"])

// Reactive state
const range = ref([])

// Computed property to watch props
const propsToWatch = computed(() => ({
    pages: props.pages,
    currentPage: props.currentPage,
    timestamp: Date.now(),
}))

// Watch for changes in props
watch(
    propsToWatch,
    () => {
        organisePageLinks()
    },
    { immediate: true },
)

// Method to organize page links
function organisePageLinks() {
    range.value = []
    for (let i = 1; i <= props.pages; i++) {
        if (
            i === 1 || // first page
            i === props.pages || // last page
            i === props.currentPage || // current page
            i === props.currentPage - 1 || // page before current
            i === props.currentPage + 1 || // page after current
            (i <= 4 && props.currentPage < 4) || // "filler" links at start
            (i >= props.pages - 3 && props.currentPage > props.pages - 3) // "filler" links at end
        ) {
            const index = range.value.length
            if (index > 0 && range.value[index - 1] < i - 1) {
                // Add "..." for skipped pages
                range.value.push("...")
            }
            range.value.push(i)
        }
    }
}

// Method to get the page
function getPage(page) {
    emit("page-changed", page)
}

// Methods to go to previous or next page
function getPreviousPage() {
    getPage(props.currentPage - 1)
}

function getNextPage() {
    getPage(props.currentPage + 1)
}
</script>
