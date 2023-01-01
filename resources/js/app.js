import axios from 'axios';
import Base from './base';
import moment from 'moment-timezone';
import Routes from './routes';
import Vue from 'vue';
import VueJsonPretty from 'vue-json-pretty';
import VueRouter from 'vue-router';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/ar-sa';

const token = document.head.querySelector('meta[name="csrf-token"]');
const access_token = localStorage.getItem('access_token');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

if (access_token) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + access_token;
}

window.bus = new Vue();
Vue.use(VueRouter);

moment.tz.setDefault('utc');

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: '/admin',
});

router.beforeEach((to, from, next) => {
    if (to.meta.createTitle)
        to.meta.title = to.meta.createTitle(to.params);

    document.title = 'Aktaa Dental - ' + to.meta.title;

    next();
});

axios.interceptors.response.use(function (response) {
    return response
}, function (error) {
    bus.$emit('flash-message', {text: error.response.data.message, type: 'error'});
    if (error.response.status === 401) {
        localStorage.removeItem('user')
        app.user = null;
        setTimeout(() => router.push('/login'), 400)
    }
    return Promise.reject(error)
})

Vue.component('vue-json-pretty', VueJsonPretty);

// Components
Vue.component('async-button', require('./components/AsyncButton.vue').default);
Vue.component('bar-chart', require('./components/BarChart.vue').default);
Vue.component('pie-chart', require('./components/PieChart.vue').default);
Vue.component('file-pond-component', require('./components/FilePond.vue').default);
Vue.component('search', require('./components/Search.vue').default);
Vue.component('search-details', require('./components/SearchDetails.vue').default);
Vue.component('search-empty-results', require('./components/SearchEmptyResults.vue').default);
Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('metric', require('./components/Metric.vue').default);
Vue.component('popover', require('./components/Popover.vue').default);
Vue.component('pagination', require('./components/Pagination.vue').default);

// Icons
Vue.component('icon-arrow-down', require('./components/icons/ArrowDown.vue').default);
Vue.component('icon-arrow-up', require('./components/icons/ArrowUp.vue').default);
Vue.component('icon-refresh', require('./components/icons/Refresh.vue').default);
Vue.component('icon-search', require('./components/icons/Search.vue').default);
Vue.component('icon-cloud', require('./components/icons/Cloud.vue').default);
Vue.component('icon-collection', require('./components/icons/Collection.vue').default);
Vue.component('icon-bars', require('./components/icons/Bars.vue').default);
Vue.component('icon-users', require('./components/icons/users.vue').default);
Vue.component('icon-exclamation', require('./components/icons/Exclamation.vue').default);
Vue.component('icon-desktop-computer', require('./components/icons/DesktopComputer.vue').default);
Vue.component('icon-dots-vertical', require('./components/icons/DotsVertical.vue').default);
Vue.component('icon-loader', require('./components/icons/Loader.vue').default);
Vue.component('icon-flag', require('./components/icons/Flag.vue').default);
Vue.component('icon-calendar', require('./components/icons/Calendar.vue').default);
Vue.component('icon-file', require('./components/icons/File.vue').default);
Vue.component('icon-list', require('./components/icons/List.vue').default);
Vue.component('icon-clipboard-copy', require('./components/icons/ClipboardCopy.vue').default);
Vue.component('icon-chevron-right', require('./components/icons/ChevronRight.vue').default);
Vue.component('icon-eye', require('./components/icons/Eye.vue').default);
Vue.component('icon-edit', require('./components/icons/Edit.vue').default);
Vue.component('icon-delete', require('./components/icons/Delete.vue').default);
Vue.component('icon-chart-bar', require('./components/icons/ChartBar.vue').default);
Vue.component('icon-terminal', require('./components/icons/Terminal.vue').default);
Vue.component('icon-x-circle', require('./components/icons/XCircle.vue').default);
Vue.component('icon-information-circle', require('./components/icons/InformationCircle.vue').default);
Vue.component('icon-expenses', require('./components/icons/expenses.vue').default);
Vue.component('icon-money', require('./components/icons/money.vue').default);
Vue.component('icon-debit', require('./components/icons/debit.vue').default);
Vue.component('icon-restore', require('./components/icons/restore.vue').default);

Vue.component('flash-message', require('./components/FlashMessage.vue').default);

Vue.mixin(Base);

Vue.filter('numberFormat', function (value) {
    return value ? value.toLocaleString(
        undefined, // leave undefined to use the visitor's browser locale or a string like 'en-US' to override it.
        {minimumFractionDigits: 2}
    ) : '0';
})

Vue.directive('click-outside', {
    bind: function (el, binding, vnode) {
        el.clickOutsideEvent = function (event) {
            // here I check that click was outside the el and his children
            if (!(el == event.target || el.contains(event.target))) {
                // and if it did, call method provided in attribute value
                vnode.context[binding.expression](event);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent)
    },
    unbind: function (el) {
        document.body.removeEventListener('click', el.clickOutsideEvent)
    },
});

app = new Vue({
    el: '#vapor-ui',
    router,
    data() {
        return {
            user: null,
            show: false,
            showSidebar: false,
        }
    },
    mounted() {
        function isVisible(e) {
            return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length);
        }

        this.user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null;
        window.user = this.user;
    },
    methods: {
        logout() {
            axios.post('/api/logout' + (this.user.admin ? '-admin' : '')).then(({data}) => {
                localStorage.clear();
                this.$router.push({name: 'login'}).then(() => {
                    bus.$emit('flash-message', {text: data.message, type: 'success'})
                    this.user = null;
                    this.show = false;
                })
            })
        },
        toggleDropdown() {
            this.show = !this.show
        },
        closeDropdown() {
            console.log('clicked')
            return this.show = false;
        },
        toggleSideBar() {
            this.showSidebar = !this.showSidebar
        }
    }
});
