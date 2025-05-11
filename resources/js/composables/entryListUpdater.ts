import { onUnmounted, unref, watch } from "vue"
import { EntryListStore } from "@/store/factories/entryListStore"
import { deepUnref } from "@/logic/deepUnref"
import { api, REQUEST_ABORTED } from "@/logic/api"
// import { useSnackbarsStore } from "@/store/snackbars"
import { MaybeRef, useThrottleFn } from "@vueuse/core"
import { isLiteralObjectOrArray } from "@/logic/helpers"

type ReloadOptions = {
    silently?: boolean
}

function abortRunningRequests(activeAbortControllers: Set<AbortController>) {
    activeAbortControllers.forEach((abortController) => {
        abortController.abort()
    })
    activeAbortControllers.clear()
}

async function callFetchFunction<Store extends EntryListStore>(endpoint: string, store: Store, callback: ((response: any) => Promise<void>) | null, activeAbortControllers: Set<AbortController>) {
    abortRunningRequests(activeAbortControllers)

    const abortController = new AbortController()
    activeAbortControllers.add(abortController)
    const response = await api.post(endpoint, deepUnref(store.entryListRequestParams), abortController)

    store.entries = response.entries

    store.pagination.page = response.pagination.page
    store.pagination.last_page = response.pagination.last_page
    store.pagination.per_page = response.pagination.per_page
    store.pagination.total = response.pagination.total
    if (response.page && store.pagination.page !== response.page) {
        store.pagination.page = response.page
    }
    if (callback) {
        await callback(response)
    }
}

function downloadExcelFactory<Store extends EntryListStore>(endpoint: string, store: Store) {
    return async (tableColumns: MaybeRef<Record<string, any>[]>) => {
        const data = deepUnref(store.entryListRequestParams)
        if (data.excelDownloadTableHeaders !== undefined) {
            throw new Error("You may not define the key excelDownloadTableHeaders in your entryListRequestParams.")
        }
        data.excelDownloadTableHeaders = unref(tableColumns).filter((header) => {
            return header.hideInDownload !== true
        })
        // This will download the file directly
        await api.post(endpoint + "/download", data)
    }
}

export function useEntryListUpdater<Store extends EntryListStore>(endpoint: string, store: Store, callback: ((response: any) => Promise<void>) | null = null) {
    const activeAbortControllers = new Set<AbortController>()
    let isUnregistered = false
    const loadData = async (options: ReloadOptions = {}) => {
        if (isUnregistered) {
            return
        }
        if (!options.silently) {
            store.isLoading = true
        }
        try {
            await callFetchFunction(endpoint, store, callback, activeAbortControllers)

            store.dataLoadedCallbacks.forEach((callback) => {
                if (typeof callback === "function") {
                    callback(store)
                } else if (typeof callback === "object" && typeof callback.handler === "function") {
                    callback.handler(store)
                }
            })

            store.dataLoadedCallbacks = store.dataLoadedCallbacks.filter((callback) => typeof callback === "function" || !callback.once)
        } catch (e) {
            // A dom exception indicates that the request was aborted
            if (e === REQUEST_ABORTED || e instanceof DOMException) {
                return
            }
            // const snackbarsStore = useSnackbarsStore()
            let errorMessage = "Die Daten konnten nicht geladen werden. Bitte versuchen Sie es später erneut."
            if (typeof e === "string") {
                errorMessage = e
            } else {
                // We want to log unknown errors
                // eslint-disable-next-line
        console.error(e)
            }
            // snackbarsStore.error(errorMessage)
        }

        if (!options.silently) {
            setTimeout(() => {
                store.isLoading = false
            }, 200)
        }
    }
    const throttledLoadData = useThrottleFn(loadData, 800, true, true)

    const stopConfigWatcher = watch(
        () => store.configWatcher,
        async (newConfig, oldConfig) => {
            newConfig = isLiteralObjectOrArray(newConfig) ? JSON.stringify(newConfig) : newConfig
            oldConfig = isLiteralObjectOrArray(oldConfig) ? JSON.stringify(oldConfig) : oldConfig
            // Only refresh the data if the config actually changed
            if (newConfig !== oldConfig) {
                await throttledLoadData()
            }
        },
        {
            // We set flush: post here, so that other watchers get triggered first and can potentially update the store settings
            // like for example setting the pagination to the first page when a filter was changed.
            flush: "post",
        },
    )

    // Reset the pagination page when basic filters change
    watch([() => store.filters, () => store.query, () => store.tags], () => {
        store.pagination.page = 1
    })

    // Load the initial data
    const initialLoadPromise = throttledLoadData()

    function unregister() {
        stopConfigWatcher()
        abortRunningRequests(activeAbortControllers)
        isUnregistered = true
    }

    onUnmounted(() => {
        unregister()
    })

    return {
        reload(options: ReloadOptions = {}) {
            return loadData(options)
        },
        initialLoadPromise,
        unregister,
        downloadExcel: downloadExcelFactory(endpoint, store),
    }
}
