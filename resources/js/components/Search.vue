<template>
    <div class="flex-1 relative pb-8 z-0 overflow-y-auto">
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200 lg:border-none">
            <div class="flex-1 px-4 flex justify-between sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8 items-center">
                <div v-show="!hide" class="flex-1 flex">
                    <div class="w-full flex md:ml-0">
                        <label for="search-input" class="sr-only">ابحث عن</label>
                        <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <c-icon-search size="5" />
                            </div>
                            <input id="search-input" v-model="state.filters.query" class="block w-full h-full pl-8 pr-3 py-5 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 sm:text-sm" placeholder="ابحث عن" type="search" @input.stop="search" />
                        </div>
                    </div>
                </div>
                <div :class="`flex items-center ${hide ? 'ml-auto' : ' ml-4  md:ml-6'}`">
                    <button class="p-1 text-gray-400 rounded-full hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:shadow-outline focus:text-gray-500" aria-label="Refresh" @click="loadEntries">
                        <c-icon-refresh size="6" />
                    </button>
                </div>
                <slot name="create-btn"></slot>
            </div>
        </div>

        <div class="flex-1 relative pb-8 z-0 overflow-y-auto">
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
                    <div class="py-6 md:flex md:items-center md:flex-wrap md:justify-between lg:border-t lg:border-cool-gray-200">
                        <div v-if="showDatesFilters" class="grid grid-cols-2 gap-6 min-w-0 w-full">
                            <div class="">
                                <label for="from-date-input" class="block text-sm font-medium leading-5 text-gray-700"> من تاريخ </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <!--                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
                                    <!--                                        <c-icon-calendar class="text-gray-400" size="5"/>-->
                                    <!--                                    </div>-->
                                    <!--                                    <date-picker id="from-date-input" v-model="state.filters.fromDate" class="" @change="loadEntries"></date-picker>-->
                                    <!--                                    <div class="absolute inset-y-0 right-0 flex items-center">-->
                                    <!--                                        <select-->
                                    <!--                                            v-model="minutesAgo"-->
                                    <!--                                            v-on:change="onMinutesAgoChange()"-->
                                    <!--                                            aria-label="Currency"-->
                                    <!--                                            class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5"-->
                                    <!--                                        >-->
                                    <!--                                            <option-->
                                    <!--                                                v-for="[option, label] in getMinutesAgoOptions()"-->
                                    <!--                                                :key="`minutes-ago-${option}`"-->
                                    <!--                                                :value="option"-->
                                    <!--                                            >-->
                                    <!--                                                {{ label }}-->
                                    <!--                                            </option>-->
                                    <!--                                        </select>-->
                                    <!--                                    </div>-->
                                </div>
                                <template v-if="state.errors.length">
                                    <p v-for="error in state.errors" :key="error" class="mt-2 text-sm text-red-600">
                                        {{ error }}
                                    </p>
                                </template>
                            </div>
                            <div class="">
                                <label for="start-time-input" class="block text-sm font-medium leading-5 text-gray-700"> الى تاريخ </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <!--                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
                                    <!--                                        <c-icon-calendar class="text-gray-400" size="5"/>-->
                                    <!--                                    </div>-->
                                    <!--                                    <date-picker id="start-time-input" v-model.lazy="state.filters.toDate" :min="state.filters.fromDate" class="" @change="loadEntries"></date-picker>-->
                                    <!--                                    <div class="absolute inset-y-0 right-0 flex items-center">-->
                                    <!--                                        <select-->
                                    <!--                                            v-model="minutesAgo"-->
                                    <!--                                            v-on:change="onMinutesAgoChange()"-->
                                    <!--                                            aria-label="Currency"-->
                                    <!--                                            class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5"-->
                                    <!--                                        >-->
                                    <!--                                            <option-->
                                    <!--                                                v-for="[option, label] in getMinutesAgoOptions()"-->
                                    <!--                                                :key="`minutes-ago-${option}`"-->
                                    <!--                                                :value="option"-->
                                    <!--                                            >-->
                                    <!--                                                {{ label }}-->
                                    <!--                                            </option>-->
                                    <!--                                        </select>-->
                                    <!--                                    </div>-->
                                </div>
                                <template v-if="state.errors.length">
                                    <p v-for="error in state.errors" :key="error" class="mt-2 text-sm text-red-600">
                                        {{ error }}
                                    </p>
                                </template>
                            </div>
                        </div>

                        <slot name="filters" :load-entries="loadEntries" :filters="state.filters" :total-values="state.totalValues"></slot>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 mb-32">
            <div class="flex flex-col mt-2">
                <!-- Loader -->
                <c-loader v-if="state.searching && state.entries.length === 0">
                    <template v-if="state.cursor">لم يتم العثور على إدخالات حتى الآن ، لا يزال البحث ...</template>
                </c-loader>

                <!-- No Search Results -->
                <c-search-empty-results v-if="!state.searching && !state.troubleshooting && state.entries.length === 0"> لم يتم العثور على إدخالات لبيانات البحث المقدمة. </c-search-empty-results>

                <!-- state.troubleshooting -->
                <template v-if="!state.searching && state.troubleshooting">
                    <div class="px-6 py-4 bg-white shadow-md rounded-lg">
                        <!-- Create Your First Project -->
                        <div class="flex items-center">
                            <c-icon-exclamation :size="6" />

                            <div class="ml-3 font-semibold text-sm text-gray-600 uppercase tracking-wider">Server Error</div>
                        </div>

                        <div class="mt-3 text-sm text-gray-700">
                            <template v-if="!$slots['troubleshooting']">
                                <p>{{ $t("debits.errorMessage") }}</p>

                                <p class="mt-2">{{ $t("debits.suggestRecentDate") }}</p>
                            </template>
                            <slot v-else name="troubleshooting"></slot>
                        </div>
                    </div>
                </template>

                <div v-if="!state.troubleshooting" class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                    <div v-if="state.entries.length > 0" class="bg-white min-w-full">
                        <div class="bg-gray-50 border-b border-gray-200 flex font-medium items-center justify-between leading-4 px-6 py-4 w-full">
                            <span class="text-gray-500 text-left text-lg font-bold tracking-wider">{{ title }}</span>
                        </div>

                        <!--                        <div-->
                        <!--                            v-if="page * 10 < total"-->
                        <!--                            class="flex w-full py-4 whitespace-no-wrap text-sm leading-5 text-cool-gray-900 text-center border-b border-gray-200"-->
                        <!--                        >-->
                        <!--                            <c-icon-loader v-if="state.searching" class="flex-1 mt-2" size="3"/>-->
                        <!--                            <p v-else class="flex-1 text-sm">-->
                        <!--                                <a-->
                        <!--                                    href="#"-->
                        <!--                                    v-on:click.prevent="loadMore"-->
                        <!--                                    class="no-underline hover:underline text-blue-500 text-sm"-->
                        <!--                                >-->
                        <!--                                    Load newer entries-->
                        <!--                                </a>-->
                        <!--                            </p>-->
                        <!--                        </div>-->
                    </div>

                    <table v-if="state.entries.length > 0" class="bg-white min-w-full divide-y divide-gray-200">
                        <thead>
                            <slot name="head"></slot>
                        </thead>
                        <transition-group tag="tbody" name="list" class="divide-y divide-gray-200">
                            <tr v-for="entry in state.entries" :key="entry.id">
                                <slot name="row" :entry="entry"></slot>
                            </tr>
                        </transition-group>
                    </table>
                    <div v-if="state.entries.length > 0" class="flex justify-between items-center bg-white py-2 px-3">
                        <!-- Help text -->

                        <nav class="flex justify-center items-center gap-x-1" aria-label="Pagination">
                            <button
                                type="button"
                                :disabled="state.pagination.current_page === 1"
                                class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                                aria-label="Previous"
                                @click="prev"
                            >
                                <svg class="shrink-0 size-3.5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6"></path>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </button>
                            <div class="flex items-center gap-x-1">
                                <span
                                    class="min-h-[38px] min-w-[38px] flex justify-center items-center border border-gray-200 text-gray-800 py-2 px-3 text-sm rounded-lg focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-white dark:focus:bg-white/10"
                                >
                                    {{ state.pagination.current_page }}
                                </span>
                                <span class="min-h-[38px] flex justify-center items-center text-gray-500 py-2 px-1.5 text-sm dark:text-neutral-500">of</span>
                                <span class="min-h-[38px] flex justify-center items-center text-gray-500 py-2 px-1.5 text-sm dark:text-neutral-500">
                                    {{ state.pagination.last_page }}
                                </span>
                            </div>
                            <button
                                :disabled="state.pagination.current_page === state.pagination.last_page"
                                type="button"
                                class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                                aria-label="Next"
                                @click="next"
                            >
                                <span class="sr-only">Next</span>
                                <svg class="shrink-0 size-3.5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </button>
                        </nav>
                        <div>
                            <CSelect v-model="state.pagination.per_page" :options="pageOptions" @change="search"></CSelect>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount, onMounted, reactive, ref, watch } from "vue"
import axios from "axios"
import { useRoute } from "vue-router"
import { useRouteQueryParam } from "@/logic/routeQuerySync"

// Props
withDefaults(
    defineProps<{
        hide?: boolean
        showDatesFilters?: boolean
    }>(),
    {
        hide: false,
        showDatesFilters: false,
    },
)

// Reactive state
const state = reactive({
    entries: [],
    errors: [],
    total: null,
    troubleshooting: false,
    minutesAgo: null,
    searching: true,
    cursor: null,
    filters: {
        query: "",
        fromDate: "",
        toDate: "",
        date: "",
    },
    item: {},
    pagination: {
        current_page: 1,
        per_page: 10,
        last_page: 1,
    },
    page: 1,
    currentTab: 1,
    totalValues: 0,
})

useRouteQueryParam("query", "", "string", { targetRef: state.filters.query })
// Reactive variables
const group = ref("")
const title = ref("")
const route = useRoute()
const pageOptions = [
    { id: 10, label: 10 },
    { id: 20, label: 20 },
    { id: 50, label: 50 },
    { id: 100, label: 100 },
]

// Watchers
watch(
    () => route.fullPath,
    () => {
        if ((route.name! as string).includes("index")) {
            title.value = route.meta?.title as unknown as string
        }
        if (route.params.group !== group.value) {
            group.value = route.params.group as string
            loadEntries()
        }
    },
    { immediate: true },
)

// Load entries
function loadEntries() {
    state.entries = []
    state.cursor = null
    request()
        .then(({ data }) => {
            state.searching = false
            state.entries = data.entries
            state.item = data.item || {}
            state.pagination = data.pagination
            state.total = data.pagination.total
            state.cursor = data.cursor
            if (data.totalValues) {
                state.totalValues = data.totalValues
            }
            if (state.entries.length < 50 && state.cursor) {
                loadMore()
            }
        })
        .catch(() => {
            state.searching = false
        })
}

// API request
function request() {
    const filters = { ...state.filters }
    const params = { ...state.filters }

    if (state.filters.fromDate) {
        params.fromDate = parse(state.filters.fromDate, "YYYY-MM-DD").addDays(1)
    }
    if (state.filters.toDate) {
        params.toDate = parse(state.filters.toDate, "YYYY-MM-DD").addDays(1)
    }
    if (state.filters.date) {
        params.date = parse(state.filters.date, "YYYY-MM-DD").addDays(1)
    }

    let resource = route.meta.resource
    Object.entries(route.params).forEach(([param, value]) => {
        if (resource.includes(`:${param}`)) {
            resource = resource.replace(`:${param}`, value)
        }
    })

    let queryParams = ""
    if (route.meta.queryParams) {
        Object.entries(route.meta.queryParams).forEach(([key, value]) => {
            queryParams += `&${key}=${value}`
        })
    }

    state.searching = true
    return axios
        .get(`/api/${resource}?page=${state.page}&per_page=${state.pagination.per_page}${queryParams}`, { params })
        .then((data) => {
            state.troubleshooting = false
            if (JSON.stringify(filters) !== JSON.stringify(state.filters)) {
                throw "The filters have been changed."
            }
            return data
        })
        .catch(({ response }) => {
            state.searching = false
            state.troubleshooting = true
            return response
        })
}

// Pagination methods
function search() {
    state.page = 1
    loadEntries()
}

function prev() {
    state.page -= 1
    loadEntries()
}

function next() {
    state.page += 1
    loadEntries()
}

function first() {
    state.page = 1
    loadEntries()
}

function last() {
    state.page = state.pagination.last_page
    loadEntries()
}

function loadMore() {
    state.page += 1
    request().then(({ data }) => {
        state.entries = data.entries
        state.total = data.pagination.total
        state.pagination = data.pagination
    })
}

// Focus on search
function focusOnSearch() {
    document.onkeyup = (event) => {
        if (event.which === 191 || event.key === 191) {
            const searchInput = document.getElementById("search-input")
            if (searchInput) {
                searchInput.focus()
            }
        }
    }
}

// Mount lifecycle hook
onMounted(() => {
    group.value = route.params.group as string
    title.value = route.meta!.title as string
    loadEntries()
    focusOnSearch()

    // bus.$on("item-deleted", loadEntries).$on("item-created", loadEntries).$on("item-updated", loadEntries)
})

// Cleanup on unmount
onBeforeUnmount(() => {
    state.filters = {
        query: "",
        fromDate: "",
        toDate: "",
        date: "",
    }
    document.onkeyup = null
})
</script>
