import axios from "axios"
import App from "@/layout/App.vue"
//
const token = document.head.querySelector('meta[name="csrf-token"]')
const access_token = localStorage.getItem("access_token")

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content
}

if (access_token) {
    axios.defaults.headers.common["Authorization"] = "Bearer " + access_token
}

import "../css/app.css"
import "../css/vapor-ui.css"
import { createApp, markRaw, onMounted, ref } from "vue"
import { createPinia } from "pinia"
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import router from "./router"
import { useRouter } from "vue-router"
import { loadComponents } from "./clinicComponents"
import { setupI18n } from "./i18n"

const pinia = createPinia()
pinia.use(({ store }) => {
    store.router = markRaw(router)
})
pinia.use(piniaPluginPersistedstate)

const app = createApp(App)
app.use(router)
app.use(pinia)
loadComponents(app)
setupI18n(app)

app.mount("#vapor-ui")
