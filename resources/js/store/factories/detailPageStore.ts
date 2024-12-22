import { _GettersTree, _StoreWithGetters, _StoreWithState, defineStore, DefineStoreOptions, PiniaCustomProperties, StateTree, StoreDefinition } from "pinia"
import { getDetailPageOutletRoute, getRootRoutePath } from "@/logic/detailPage"
import { nextTick, UnwrapRef, watch } from "vue"
import { AttributeChangedReturn as WatcherAttributes, useCheckStoreAttributeChanged, WatcherSettings } from "@/composables/checkStoreAttributeChanged"
import { MaybeRef } from "@vueuse/core"
import { useScrollElementIntoView } from "@/composables/scrollElementIntoView"

export type SubPage = {
    title: string
    isDirty: MaybeRef<boolean>
    visible?: ((store: DetailPageStore) => MaybeRef<boolean>) | MaybeRef<boolean>
    buildSubPath?: (this: any) => string
    icon?: string
}

export type DetailPageStateTree = {
    entryId: number
    isLoading: boolean
    subPages: Record<string, SubPage>
    errors?: Record<string, any>
    watchers?: Record<string, WatcherAttributes>
}

export type DetailPageGetters = {
    isNewEntry: boolean
    hasSubPages: boolean
    hasUnsavedChanges: boolean
    subPageLinks: Record<string, string>
}

export type DetailPageActions = {
    markAsDirty: (subPage?: string) => void
    markAsClean: (subPage?: string) => void
    loadData: () => Promise<void>
    addWatcher: (attribute: string, settings?: WatcherSettings) => void
    getPageContentWrapper: () => HTMLElement | null
    getFirstElementWithError: () => HTMLElement | null
    scrollDetailsFormToFirstError: () => void
}

export declare type DetailPageStore = ReturnType<ReturnType<typeof defineDetailPageStore>>

export type DetailPageStoreDefinition<Id extends string, S extends StateTree, G, A> = StoreDefinition<Id, S, G & DetailPageGetters, A & DetailPageActions>

// We only need to hard code the return type here, because PHPStorm doesn't correctly infer the type (works in VSCode)
export function defineDetailPageStore<Id extends string, S extends DetailPageStateTree, G extends _GettersTree<S>, A>(id: Id, options: Omit<DefineStoreOptions<Id, S, G, A>, "id">): DetailPageStoreDefinition<Id, S, G, A> {
    const additionalGetters: ThisType<UnwrapRef<DetailPageStateTree> & _StoreWithGetters<G> & PiniaCustomProperties> & _GettersTree<DetailPageStateTree> = {
        isNewEntry(state) {
            return state.entryId === -1
        },
        hasSubPages(state) {
            return Object.keys(state.subPages).length > 0
        },
        hasUnsavedChanges(): boolean {
            return Object.values(this.subPages).some((subPage) => subPage.isDirty)
        },
        subPageLinks(state) {
            const links = {} as Record<keyof typeof state.subPages, string>
            const currentRoute = this.router.currentRoute
            Object.keys(state.subPages).forEach((subPageKey) => {
                let buildSubPath = state.subPages[subPageKey].buildSubPath
                if (!buildSubPath) {
                    buildSubPath = () => {
                        return `/${state.entryId}/${subPageKey}`
                    }
                }

                const rootRoutePathParts = getRootRoutePath(currentRoute).split("?").filter(Boolean)
                const detailPageOutletRoute = getDetailPageOutletRoute(currentRoute)
                links[subPageKey] = detailPageOutletRoute + buildSubPath.apply(this)
                if (rootRoutePathParts.length > 1) {
                    links[subPageKey] += `?${rootRoutePathParts[1]}`
                }
            })
            return links
        },
    }
    const additionalActions: DetailPageActions & ThisType<DetailPageActions & UnwrapRef<DetailPageStateTree> & _StoreWithState<Id, DetailPageStateTree, DetailPageGetters, DetailPageActions> & _StoreWithGetters<DetailPageGetters> & PiniaCustomProperties> = {
        async markAsDirty(subPage?: string) {
            if (subPage) {
                this.subPages[subPage].isDirty = true
                return
            }
            const currentRoute = this.router.currentRoute.value
            // making sure to also check in the parent chain of matched routes if any of those matches
            const currentSubPageEntry = Object.entries(this.subPageLinks).find(([, path]) => {
                // The search parameters in currentRoute.fullPath are already URI encoded, so to compare them we have to decode them first
                if (decodeURI(path) === decodeURI(currentRoute.fullPath)) {
                    return true
                }
            })
            if (!currentSubPageEntry) {
                return
            }
            this.subPages[currentSubPageEntry[0]].isDirty = true
        },
        async markAsClean(subPage?: string) {
            if (subPage) {
                this.subPages[subPage].isDirty = false
                return
            }
            Object.values(this.subPages).forEach((subPage) => (subPage.isDirty = false))
        },
        async loadData() {
            throw "Please implement the loadData action in your detailPageStore."
        },
        addWatcher(attribute: string, settings = {}) {
            if (typeof this.watchers === "undefined") {
                throw "Please instantiate `watchers` attribute before!"
            }
            this.watchers[attribute] = useCheckStoreAttributeChanged(this, attribute, settings)

            watch(
                // @ts-ignore
                () => this.watchers && this.watchers[attribute].isChanged,
                (value) => {
                    if (value) {
                        this.markAsDirty()
                    } else {
                        this.markAsClean()
                    }
                },
            )
        },
        getPageContentWrapper(): HTMLElement | null {
            return document.querySelector(".c-detailPage__main")
        },
        getFirstElementWithError(): HTMLElement | null {
            if (this.errors && Object.keys(this.errors).length) {
                return document.querySelector(`.c-detailPage__main .c-field--error`)
            }
            return null
        },
        scrollDetailsFormToFirstError(): void {
            nextTick().then(() => {
                useScrollElementIntoView(this.getPageContentWrapper(), this.getFirstElementWithError()).scrollToTop()
            })
        },
    }

    const combinedOptions = {
        state: options.state,
        getters: {
            ...additionalGetters,
            ...options.getters,
        },
        actions: {
            ...additionalActions,
            ...options.actions,
        },
    }

    return defineStore(id, combinedOptions) as unknown as DetailPageStoreDefinition<Id, S, G, A>
}
