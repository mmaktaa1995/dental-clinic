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
                            <slot name="troubleshooting"></slot>
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
                    <div v-if="state.entries.length > 0" class="flex justify-between items-center bg-white divide-y divide-gray-200 py-2 px-3">
                        <!-- Help text -->
                        <span class="text-sm text-gray-700 dark:text-gray-400">
                            عرض
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ (state.pagination.current_page - 1) * state.pagination.per_page + 1 }}
                            </span>
                            إلى
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ state.pagination.current_page * state.pagination.per_page }}
                            </span>
                            من <span class="font-semibold text-gray-900 dark:text-white">{{ state.total }}</span> من الصفوف
                        </span>
                        <!-- Buttons -->
                        <div class="inline-flex xs:mt-0">
                            <button
                                :disabled="state.pagination.current_page === 1"
                                :class="{ 'cursor-not-allowed bg-gray-200 hover:bg-gray-800': state.pagination.current_page === 1 }"
                                class="py-2 px-4 text-sm text-white bg-gray-700 rounded-r hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white focus:outline-none focus:shadow-none"
                                @click="first"
                            >
                                الأول
                            </button>
                            <button
                                :disabled="state.pagination.current_page === 1"
                                :class="{ 'cursor-not-allowed bg-gray-200 hover:bg-gray-800': state.pagination.current_page === 1 }"
                                class="py-2 px-4 text-sm text-white bg-gray-800 hover:bg-gray-800 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white focus:outline-none focus:shadow-none"
                                @click="prev"
                            >
                                السابق
                            </button>
                            <button
                                :disabled="state.pagination.current_page === state.pagination.last_page"
                                :class="{ 'cursor-not-allowed bg-gray-200 hover:bg-gray-800': state.pagination.current_page === state.pagination.last_page }"
                                class="py-2 px-4 text-sm text-white bg-gray-800 border-0 border-r border-gray-700 hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white focus:outline-none focus:shadow-none"
                                @click="next"
                            >
                                التالي
                            </button>
                            <button
                                :disabled="state.pagination.current_page === state.pagination.last_page"
                                :class="{ 'cursor-not-allowed bg-gray-200 hover:bg-gray-800': state.pagination.current_page === state.pagination.last_page }"
                                class="py-2 px-4 text-sm text-white bg-gray-700 rounded-l border-0 border-r border-gray-700 hover:bg-gray-800 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white focus:outline-none focus:shadow-none"
                                @click="last"
                            >
                                الأخير
                            </button>
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

// Reactive variables
const group = ref("")
const title = ref("")
const route = useRoute()

// Watchers
watch(
    () => route.fullPath,
    () => {
        console.log(route.name, route.meta)
        console.log((route.name! as string).includes("index"))
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
        .get(`/api/${resource}?page=${state.page}${queryParams}`, { params })
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
