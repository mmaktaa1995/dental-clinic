<template>
    <div class="flex-1 relative pb-8 z-0 overflow-y-auto">
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200 lg:border-none">
            <div class="flex-1 px-4 flex justify-between sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8 items-center">
                <div class="flex-1 flex" v-show="!hide">
                    <div class="w-full flex md:ml-0">
                        <label for="search-input" class="sr-only">ابحث عن</label>
                        <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <icon-search size="5"/>
                            </div>
                            <input
                                id="search-input"
                                v-model="filters.query"
                                @input.stop="search"
                                class="block w-full h-full pl-8 pr-3 py-5 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 sm:text-sm"
                                placeholder="ابحث عن"
                                type="search"
                            />
                        </div>
                    </div>
                </div>
                <div :class="`flex items-center ${hide?'ml-auto':' ml-4  md:ml-6'}`">
                    <button
                        class="p-1 text-gray-400 rounded-full hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:shadow-outline focus:text-gray-500"
                        aria-label="Refresh"
                        v-on:click="loadEntries"
                    >
                        <icon-refresh size="6"/>
                    </button>
                </div>
                <slot name="create-btn"></slot>
            </div>
        </div>

        <div class="flex-1 relative pb-8 z-0 overflow-y-auto">
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
                    <div class="py-6 md:flex md:items-center md:flex-wrap md:justify-between lg:border-t lg:border-cool-gray-200">
                        <div class="grid grid-cols-2 gap-6 min-w-0 w-full" v-if="showDatesFilters">
                            <div class="">
                                <label for="from-date-input" class="block text-sm font-medium leading-5 text-gray-700">
                                    من تاريخ
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
<!--                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
<!--                                        <icon-calendar class="text-gray-400" size="5"/>-->
<!--                                    </div>-->
                                    <date-picker
                                        v-model.lazy="filters.fromDate"
                                        v-on:change="loadEntries"
                                        id="from-date-input"
                                        class=""
                                    ></date-picker>
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
                                <p v-if="errors.length" v-for="error in errors" class="mt-2 text-sm text-red-600">
                                    {{ error }}
                                </p>
                            </div>
                            <div class="">
                                <label for="start-time-input" class="block text-sm font-medium leading-5 text-gray-700">
                                    الى تاريخ
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
<!--                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
<!--                                        <icon-calendar class="text-gray-400" size="5"/>-->
<!--                                    </div>-->
                                    <date-picker
                                        v-model.lazy="filters.toDate"
                                        v-on:change="loadEntries"
                                        id="start-time-input"
                                        :min="filters.fromDate"
                                        class=""
                                    ></date-picker>
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
                                <p v-if="errors.length" v-for="error in errors" class="mt-2 text-sm text-red-600">
                                    {{ error }}
                                </p>
                            </div>
                        </div>

                        <slot name="filters" :loadEntries="loadEntries" :filters="filters" :totalValues="totalValues"></slot>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 mb-32">
            <div class="flex flex-col mt-2">
                <!-- Loader -->
                <loader v-if="searching && entries.length === 0">
                    <template v-if="cursor">No entries have been found yet, still searching...</template>
                </loader>

                <!-- No Search Results -->
                <search-empty-results v-if="!searching && !troubleshooting && entries.length === 0">
                    لم يتم العثور على إدخالات لبيانات البحث المقدمة.
                </search-empty-results>

                <!-- Troubleshooting -->
                <template v-if="!searching && troubleshooting">
                    <div class="px-6 py-4 bg-white shadow-md rounded-lg">
                        <!-- Create Your First Project -->
                        <div class="flex items-center">
                            <icon-exclamation :size="6"/>

                            <div class="ml-3 font-semibold text-sm text-gray-600 uppercase tracking-wider">
                                Server Error
                            </div>
                        </div>

                        <div class="mt-3 text-sm text-gray-700">
                            <slot name="troubleshooting"></slot>
                        </div>
                    </div>
                </template>

                <div
                    v-if="!troubleshooting"
                    class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg"
                >
                    <div class="bg-white min-w-full" v-if="entries.length > 0">
                        <div
                            class="bg-gray-50 border-b border-gray-200 flex font-medium items-center justify-between leading-4 px-6 py-4  w-full"
                        >
                            <span class="text-gray-500 text-left text-lg font-bold tracking-wider">{{ title }}</span>

                        </div>

                        <!--                        <div-->
                        <!--                            v-if="page * 10 < total"-->
                        <!--                            class="flex w-full py-4 whitespace-no-wrap text-sm leading-5 text-cool-gray-900 text-center border-b border-gray-200"-->
                        <!--                        >-->
                        <!--                            <icon-loader v-if="searching" class="flex-1 mt-2" size="3"/>-->
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

                    <table class="bg-white min-w-full divide-y divide-gray-200 " v-if="entries.length > 0">
                        <thead>
                        <slot name="head"></slot>
                        </thead>
                        <transition-group tag="tbody" name="list" class="divide-y divide-gray-200">
                            <tr v-for="entry in entries" :key="entry.id">
                                <slot name="row" :entry="entry"></slot>
                            </tr>
                        </transition-group>
                    </table>
                    <div class="flex justify-between items-center bg-white divide-y divide-gray-200 py-2 px-3"
                         v-if="entries.length > 0">
                        <!-- Help text -->
                        <span class="text-sm text-gray-700 dark:text-gray-400">
                             عرض
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{((pagination.current_page - 1) * pagination.per_page) + 1 }}
                            </span> إلى
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{(pagination.current_page) * pagination.per_page }}
                            </span>
                            من <span class="font-semibold text-gray-900 dark:text-white">{{ total }}</span> من الصفوف
                         </span>
                        <!-- Buttons -->
                        <div class="inline-flex xs:mt-0">
                            <button :disabled="pagination.current_page === 1" @click="prev"
                                    :class="{'cursor-not-allowed bg-gray-200':pagination.current_page === 1}"
                                    class="py-2 px-4 text-sm text-white bg-gray-800 rounded-r hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white focus:outline-none focus:shadow-none">
                                السابق
                            </button>
                            <button
                                :disabled="pagination.current_page === pagination.last_page" @click="next"
                                class="py-2 px-4 text-sm text-white bg-gray-800 rounded-l border-0 border-r border-gray-700 hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white focus:outline-none focus:shadow-none">
                                التالي
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import DatePicker from 'vue2-datepicker';

export default {
    props: {
        hide: {
            default: false
        },
        showDatesFilters: {
            default: true
        }
    },
    /**
     * The component's data.
     */
    data() {
        return {
            entries: [],
            errors: [],
            total: null,
            troubleshooting: false,
            minutesAgo: null,
            searching: true,
            cursor: null,
            filters: {},
            item: {},
            pagination: {},
            page: 1,
            totalValues: 0,
        };
    },

    /**
     * Watch component's data.
     */
    watch: {
        $route(to, from) {
            if (to.params.group !== this.group) {
                this.group = this.$route.params.group;
                this.title = this.$route.meta.title;

                this.loadEntries();
            }
        },
    },

    /**
     * Prepare the component.
     */
    mounted() {
        this.group = this.$route.params.group;
        this.title = this.$route.meta.title;
        let self = this;
        // for (const [filter, value] of Object.entries(this.$route.query)) {
        //     if (this.$route.query[filter]) {
        //         this.filters[filter] = this.$route.query[filter];
        //     }
        // }

        // const startTime = this.filters.startTime
        //     ? moment.unix(this.filters.startTime)
        //     : moment().subtract(10, 'minutes');
        //
        // this.filters.startTime = startTime.local().format('YYYY-MM-DD LTS');
        this.loadEntries();
        this.focusOnSearch();

        bus.$on('item-deleted', function () {
            self.loadEntries();
        }).$on('item-created', function () {
            self.loadEntries();
        }).$on('item-updated', function () {
            self.loadEntries();
        })
    },

    /**
     * Clean after the component is destroyed.
     */
    destroyed() {
        this.filters = {};
        document.onkeyup = null;
    },

    /**
     * The component's methods.
     */
    methods: {
        loadEntries() {
            this.entries = [];
            this.cursor = null;
            this.request().then(({data}) => {
                this.searching = false;
                this.entries = data.entries;
                this.item = data.item ? data.item: {};
                this.pagination = data.pagination;
                this.total = data.pagination.total;
                this.cursor = data.cursor;
                if(data.totalValues){
                    this.totalValues = data.totalValues;
                }
                if (this.entries.length < 50 && this.cursor) {
                    this.loadMore();
                }
            }).catch(() => {

            });
        },

        /**
         * Performs a GET request on the current group.
         */
        request() {
            const filters = {...this.filters};

            const group = this.group;

            let params = {...this.filters};
            if (this.filters.fromDate) {
                params.fromDate = moment(this.filters.fromDate, 'YYYY-MM-DD').add(1, 'days');
            }
            if (this.filters.toDate) {
                params.toDate = moment(this.filters.toDate, 'YYYY-MM-DD').add(1, 'days');
            }
            if (this.filters.date) {
                params.date = moment(this.filters.date, 'YYYY-MM-DD').add(1, 'days');
            }

            let resource = this.$route.meta.resource;
            Object.entries(this.$route.params).forEach(([param, value]) => {
                if (resource.includes(`:${param}`)){
                    resource = resource.replace(`:${param}`, value)
                }
            })

            this.searching = true;
            return axios
                .get(`/api/${resource}?page=${this.page}`, {params})
                .then((data) => {
                    this.troubleshooting = false;
                    if (group !== this.group || JSON.stringify(filters) !== JSON.stringify(this.filters)) {
                        throw 'The filters have been changed.';
                    }
                    return data;
                }).catch(({response}) => {
                    this.searching = false;
                    this.troubleshooting = true;

                    return response;
                });
        },

        /**
         * Creates a new debouncer when a the search input changes.
         */
        search() {
            this.debouncer(this.page = 1);
            this.debouncer(this.loadEntries);
        },

        prev() {
            this.debouncer(--this.page);
            this.debouncer(this.loadEntries);
        },

        next() {
            this.debouncer(++this.page);
            this.debouncer(this.loadEntries);
        },

        /**
         * Using the current cursor, performs a request
         * and attach the received new entries.
         */
        loadMore() {
            this.page++;
            this.request().then(({data}) => {
                this.searching = false;
                this.entries = data.entries
                this.total = data.pagination.total;
                this.pagination = data.pagination;
            });
        },

        /**
         * Validates the filters.
         */
        validate() {
            this.errors = [];

            if (!moment(this.filters.startTime, 'YYYY-MM-DD LTS').isValid()) {
                this.errors.push("Input any valid date. Ex: '2020-05-09 2:32:43 PM'");
            }

            return !this.errors.length;
        },

        /**
         * Updates the start time, and re-load entries.
         */
        onMinutesAgoChange() {
            this.filters.startTime = moment().subtract(this.minutesAgo, 'minutes').local().format('YYYY-MM-DD LTS');

            this.loadEntries();
        },

        /**
         * Gets the minutes ago options.
         */
        getMinutesAgoOptions() {
            return Array.from(new Set([1, 5, 10, 30, this.minutesAgo].sort((a, b) => a - b))).map((value) => {
                return [value, moment().subtract(value, 'minutes').fromNow()];
            });
        },

        /**
         * Focus on the search input when "/" key is hit.
         */
        focusOnSearch() {
            document.onkeyup = (event) => {
                if (event.which === 191 || event.keyCode === 191) {
                    const searchInput = document.getElementById('search-input');

                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            };
        },
    },
    components: { DatePicker },
};
</script>
