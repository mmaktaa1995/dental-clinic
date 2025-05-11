import { onUnmounted, ref } from "vue"
import { NavigationGuardNext, onBeforeRouteLeave, onBeforeRouteUpdate, RouteLocationNormalized, useRoute, useRouter } from "vue-router"
import { getRootRoutePath } from "@/logic/detailPage"
import { useI18n } from "vue-i18n"
import { DetailPageStore } from "@/store/factories/detailPageStore"

type UnsavedChangesWarningOptions = {
    shouldHandleRouteUpdate?: (targetRoute: RouteLocationNormalized) => boolean
    disableCheckRouteUpdate?: (targetRoute: RouteLocationNormalized) => boolean
    disabledCheckRouteUpdateCallback?: (targetRoute: RouteLocationNormalized, next: NavigationGuardNext) => void
}

export function useUnsavedChangesWarning(store: DetailPageStore, options: UnsavedChangesWarningOptions = {}) {
    const route = useRoute()
    const router = useRouter()
    const { t } = useI18n()
    const isUnsavedChangesWarningOpen = ref(false)
    let unsavedChangesRedirectHandler: null | NavigationGuardNext = null

    const discardChanges = async () => {
        isUnsavedChangesWarningOpen.value = false
        if (unsavedChangesRedirectHandler) {
            unsavedChangesRedirectHandler()
            unsavedChangesRedirectHandler = null
        } else {
            await router.push(getRootRoutePath(route))
        }
        Object.values(store.subPages).forEach((subPage) => (subPage.isDirty = false))
    }

    const handleUnload = (event: BeforeUnloadEvent) => {
        if (store.hasUnsavedChanges) {
            event.preventDefault()
            return (event.returnValue = t("cDetailPage.discardChangesAlert"))
        }
    }

    function closeUnsavedChangesWarning() {
        unsavedChangesRedirectHandler = null
        isUnsavedChangesWarningOpen.value = false
    }

    function tearDown() {
        window.removeEventListener("beforeunload", handleUnload)
    }

    function handleRouteUpdate(to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) {
        if (options.disableCheckRouteUpdate) {
            if (options.disableCheckRouteUpdate(to)) {
                return options.disabledCheckRouteUpdateCallback && options.disabledCheckRouteUpdateCallback(to, next)
            }
        }
        if (options.shouldHandleRouteUpdate) {
            if (!options.shouldHandleRouteUpdate(to)) {
                next()
                return
            }
        }
        if (store.hasUnsavedChanges) {
            unsavedChangesRedirectHandler = next
            isUnsavedChangesWarningOpen.value = true
        } else {
            next()
        }
    }

    onBeforeRouteLeave(handleRouteUpdate)
    onBeforeRouteUpdate(handleRouteUpdate)
    onUnmounted(tearDown)

    return {
        isUnsavedChangesWarningOpen,
        closeUnsavedChangesWarning,
        discardChanges,
        handleUnload,
        tearDown,
    }
}
