import { _GettersTree, _StoreWithGetters, _StoreWithState, defineStore, DefineStoreOptions, PiniaCustomProperties, StateTree, Store, StoreDefinition } from "pinia"
import { UnwrapRef, WatchSource } from "vue"

export type FunctionOrHandlerObject = ((store: EntryListStore) => void) | { once?: boolean; handler: (store: EntryListStore) => void }

export type EntryListStateTree = {
    entries: null | any[]
    isLoading: boolean
    pagination: {
        page: number
        last_page: number
        total: number
        per_page: number
    }
    order: {
        by: null | string
        desc: boolean
    }
    query: string
    dataLoadedCallbacks: FunctionOrHandlerObject[]
}

export type EntryListGetters = {
    configWatcher: WatchSource
    entryListRequestParams: Record<string, any>
    paginationLength: number
}

export type EntryListActions = Record<string, never>

export declare type EntryListStore<Id extends string = string, S = EntryListStateTree, G = EntryListGetters, A = EntryListActions> = Store<Id, S & EntryListStateTree, G, A>

export type EntryListStoreDefinition<Id extends string, S extends StateTree, G, A> = StoreDefinition<Id, S, G & EntryListGetters, A & EntryListActions>
// We only need to hard code the return type here, because PHPStorm doesn't correctly infer the type (works in VSCode)
export function defineEntryListStore<Id extends string, S extends EntryListStateTree, G extends _GettersTree<S>, A>(id: Id, options: Omit<DefineStoreOptions<Id, S, G, A>, "id">): EntryListStoreDefinition<Id, S, G, A> {
    const additionalGetters: ThisType<UnwrapRef<EntryListStateTree> & _StoreWithGetters<G> & PiniaCustomProperties> & _GettersTree<EntryListStateTree> = {
        configWatcher() {
            return [this.pagination.page, this.pagination.per_page, this.query, this.order.by, this.order.desc]
        },
        entryListRequestParams() {
            return {
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                query: this.query,
                order: this.order,
            }
        },
    }

    const additionalActions: EntryListActions & ThisType<EntryListActions & UnwrapRef<EntryListStateTree> & _StoreWithState<Id, EntryListStateTree, EntryListGetters, EntryListActions> & _StoreWithGetters<EntryListGetters> & PiniaCustomProperties> = {}

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

    return defineStore(id, combinedOptions) as unknown as EntryListStoreDefinition<Id, S, G, A>
}
