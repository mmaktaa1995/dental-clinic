import { createPinia, PiniaPluginContext } from "pinia"
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import { createApp, markRaw } from "vue"
import { createHead } from "@unhead/vue"

import App from "./App.vue"
import router from "./router"
import { loadKeelearningComponents } from "./plugins/keelearningComponents"
import { api } from "./logic/api"
import { useAccountStore } from "@/modules/account/store"
import filters from "@/logic/filters"
import { initRouteQuerySync } from "@/logic/routeQuerySync"
import { useAppConfigStore } from "@/store/appConfig"
import { setupI18n } from "@/plugins/i18n"
import { getConstants } from "@/logic/constants"
import { setUserInSentry } from "@/logic/sentry"

const head = createHead()

const pinia = createPinia()
pinia.use(({ store }: PiniaPluginContext) => {
    store.router = markRaw(router)
})
pinia.use(piniaPluginPersistedstate)

initRouteQuerySync(router)

const app = createApp(App).use(router).use(pinia).use(vuetify)

app.use(head)
app.config.globalProperties.$constants = getConstants()
app.config.globalProperties.$filters = filters

loadKeelearningComponents(app)
// It's important we load vuetify after we load our components, otherwise our style overrides won't have higher specificity.
import vuetify from "./plugins/vuetify"
import { initHubspot } from "@/logic/hubspot"

const accountStore = useAccountStore()

function checkIframeAppId(appId: string) {
    if (!accountStore.isSuperAdmin) {
        return
    }
    const appConfigStore = useAppConfigStore()
    if ((appId && !appConfigStore.appId) || parseInt(appId) !== (appConfigStore.appId as number)) {
        console.log(appId, "app id from iframe")
        console.log(appConfigStore.appId, "app id from store")
        alert(
            "Es ist soeben ein Problem mit der Synchronisierung der aktiven App aufgetreten. Bitte informiere Thibaut (oder Paul) darüber und schicke idealerweise einen Screenshot von den Devtools: Application->Cookies->myadmin.keelearning.de mit. Als schnellen Lösungsweg, kannst du die cookies von myadmin.keelearning.de und admin.keelearning.de löschen.",
        )
    }
}

window.addEventListener("message", async (event) => {
    switch (event.data.type) {
        case "keelearning-iframe-navigation":
            window.location.hash = event.data.path
            checkIframeAppId(event.data.appId)
            break
        case "keelearning-iframe-loaded": {
            checkIframeAppId(event.data.appId)
            const remotePath = event.data.path.replace(/\/$/, "")
            const localPath = window.location.pathname.replace(/\/$/, "")
            // if the iframe was loaded and the paths do not match,
            // there was a navigation inside the frame
            if (remotePath !== localPath) {
                const hashPart = event.data.hash ? `/${event.data.hash}` : ""
                const redirectPath = `/${event.data.path.replace(/\/$/, "")}${event.data.search}${hashPart}`.replaceAll("//", "/")

                if (redirectPath === "/login/") {
                    const accountStore = useAccountStore()
                    await accountStore.logout()
                }
                window.location.href = redirectPath
            } else {
                // If only the "search" part changed, just change it here as well without reloading the page
                // We currently need this for the "new course" button functionality
                if (event.data.search !== window.location.search) {
                    history.replaceState({}, "", remotePath + (event.data.search || "") + event.data.hash)
                }
            }
            break
        }
        case "keelearning-iframe-navigation-request":
            await router.push(event.data.path)
            break
        case "keelearning-refresh":
            window.location.reload()
            break
    }
})

document.addEventListener("keydown", function (e) {
    if (accountStore.isSuperAdmin && e.altKey && e.ctrlKey && e.key === "k") {
        window.location.href = "/appswitcher"
    }
})

async function mountApp() {
    await setupI18n(app)
    api.setup(router)

    app.mount("#app")

    if (accountStore.isLoggedIn) {
        setUserInSentry(accountStore.id!)
    }
    initHubspot(app)
}

mountApp()
