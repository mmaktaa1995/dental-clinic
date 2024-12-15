import axios from "axios"
// import Base from './base';
// import Routes from './routes';
// import Vue from 'vue';
// import VueRouter from 'vue-router';
// import 'vue2-datepicker/index.css';
// import 'vue2-datepicker/locale/ar-sa';
// import 'vue2-datepicker/locale/ar-sa';
//
const token = document.head.querySelector('meta[name="csrf-token"]')
const access_token = localStorage.getItem("access_token")

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content
}

if (access_token) {
    axios.defaults.headers.common["Authorization"] = "Bearer " + access_token
}
//
// window.bus = new Vue();
// Vue.use(VueRouter);
//
// moment.tz.setDefault('utc');
//
// const router = new VueRouter({
//     routes: Routes,
//     mode: 'history',
//     base: '/admin',
// });
//
// router.beforeEach((to, from, next) => {
//     if (to.meta.createTitle)
//         to.meta.title = to.meta.createTitle(to.params);
//
//     document.title = 'Aktaa Dental - ' + to.meta.title;
//
//     next();
// });
//
// axios.interceptors.response.use(function (response) {
//     return response
// }, function (error) {
//     bus.$emit('flash-message', {text: error.response.data.message, type: 'error'});
//     if (error.response.status === 401) {
//         localStorage.removeItem('user')
//         app.user = null;
//         setTimeout(() => router.push('/login'), 400)
//     }
//     return Promise.reject(error)
// })
//
// import VueApexCharts from 'vue-apexcharts'
// Vue.use(VueApexCharts)
//
//
// // Components
//
//
// Vue.mixin(Base);
//
// Vue.filter('numberFormat', function (value) {
//     return value ? value.toLocaleString(
//         undefined, // leave undefined to use the visitor's browser locale or a string like 'en-US' to override it.
//         {minimumFractionDigits: 2}
//     ) : '0';
// })
//
// Vue.directive('click-outside', {
//     bind: function (el, binding, vnode) {
//         el.clickOutsideEvent = function (event) {
//             // here I check that click was outside the el and his children
//             if (!(el == event.target || el.contains(event.target))) {
//                 // and if it did, call method provided in attribute value
//                 vnode.context[binding.expression](event);
//             }
//         };
//         document.body.addEventListener('click', el.clickOutsideEvent)
//     },
//     unbind: function (el) {
//         document.body.removeEventListener('click', el.clickOutsideEvent)
//     },
// });
//

import "../css/app.css"
import "../css/vapor-ui.css"
import { createApp, markRaw, onMounted, ref } from "vue"
import { createPinia } from "pinia"
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import router from "./router"
import { useRouter } from "vue-router"
import { loadComponents } from "./clinicComponents"

const pinia = createPinia()
pinia.use(({ store }) => {
    store.router = markRaw(router)
})
pinia.use(piniaPluginPersistedstate)

const app = createApp({
    setup() {
        const user = ref(null)
        const show = ref(false)
        const showSidebar = ref(false)
        const router = useRouter()

        const isVisible = (e) => {
            return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length)
        }

        const logout = () => {
            const logoutUrl = `/api/logout${user.value?.admin ? "-admin" : ""}`
            axios.post(logoutUrl).then(({ data }) => {
                localStorage.clear()
                router.push({ name: "login" }).then(() => {
                    // Assuming `bus` is a global event bus; adapt if necessary
                    // EventBus.$emit('flash-message', {text: data.message, type: 'success'});
                    user.value = null
                    show.value = false
                })
            })
        }

        const toggleDropdown = () => {
            show.value = !show.value
        }

        const closeDropdown = () => {
            console.log("clicked")
            show.value = false
        }

        const toggleSideBar = () => {
            showSidebar.value = !showSidebar.value
        }

        onMounted(() => {
            const storedUser = localStorage.getItem("user")
            user.value = storedUser ? JSON.parse(storedUser) : null
            window.user = user.value
        })

        return {
            user,
            show,
            showSidebar,
            logout,
            toggleDropdown,
            closeDropdown,
            toggleSideBar,
        }
    },
})
app.use(router)
app.use(pinia)
loadComponents(app)

app.mount("#vapor-ui")
