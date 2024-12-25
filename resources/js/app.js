import axios from "axios"
import App from "@/layout/App.vue"
import "../css/app.css"
import "../css/vapor-ui.css"
import "@fortawesome/fontawesome-free/css/all.css"
import { createApp, markRaw } from "vue"
import { createPinia } from "pinia"
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import router from "./router"
import { loadComponents } from "./clinicComponents"
import { setupI18n } from "./i18n"
import { initRouteQuerySync } from "./logic/routeQuerySync"

//
const token = document.head.querySelector('meta[name="csrf-token"]')
const access_token = localStorage.getItem("access_token")

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content
}

if (access_token) {
    axios.defaults.headers.common["Authorization"] = "Bearer " + access_token
}

const pinia = createPinia()
pinia.use(({ store }) => {
    store.router = markRaw(router)
})
pinia.use(piniaPluginPersistedstate)

initRouteQuerySync(router)
const app = createApp(App)
app.use(router)
app.use(pinia)
loadComponents(app)
setupI18n(app)

app.mount("#vapor-ui")
