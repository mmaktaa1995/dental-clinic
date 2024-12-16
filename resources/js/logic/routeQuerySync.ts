import { LocationQueryValue, LocationQuery, Router } from "vue-router"
import { computed, nextTick, Ref, watch } from "vue"
import { syncRef } from "@vueuse/core"

type QueryValue = LocationQueryValue | LocationQueryValue[]
type SerializerType = "string" | "number" | "boolean" | "string[]" | "number[]" | "numberBooleanMap"

type Serializer<T> = {
    serialize(input: T): string | undefined
    deserialize(input: QueryValue): T | undefined
}

type ParamConfig = {
    allowEmpty?: boolean
    targetRef?: Ref
}

class RouteQueryParam<T> {
    protected serializers: Record<string, Serializer<string | number | boolean | string[] | number[] | Record<number, boolean>>> = {
        string: {
            serialize: (input: string) => input,
            deserialize: (value: QueryValue) => {
                if (typeof value === "string") {
                    return value
                }
                return undefined
            },
        },
        ["string[]"]: {
            serialize: (input: string[]) => JSON.stringify(input),
            deserialize: (value: QueryValue) => {
                if (typeof value === "string") {
                    return JSON.parse(value)
                }
                return undefined
            },
        },
        number: {
            serialize: (input: number) => {
                return input.toString()
            },
            deserialize: (value: QueryValue) => {
                if (typeof value === "string") {
                    const number = parseFloat(value)
                    if (!isNaN(number)) {
                        return number
                    }
                }

                return undefined
            },
        },
        ["number[]"]: {
            serialize: (input: number[]) => {
                return JSON.stringify(input)
            },
            deserialize: (value: QueryValue) => {
                if (typeof value === "string") {
                    return JSON.parse(value).map((value: string | number) => parseInt(`${value}`, 10))
                }
                return undefined
            },
        },
        boolean: {
            serialize: (input: boolean) => {
                return input.toString()
            },
            deserialize: (value: QueryValue) => {
                if (value === "true") {
                    return true
                }
                if (value === "false") {
                    return false
                }
                return undefined
            },
        },
        ["numberBooleanMap"]: {
            serialize: (input: Record<number, boolean>) => {
                // An object like this
                // { 5: true, 7: false }
                // Is stored in the URL like this:
                // 5:1,7:0
                return Object.entries(input)
                    .map(([key, value]) => `${key}:${value ? 1 : 0}`)
                    .join(",")
            },
            deserialize: (value: QueryValue) => {
                if (typeof value === "string") {
                    if (value === "") {
                        return {}
                    }
                    const map: Record<number, boolean> = {}
                    value.split(",").forEach((value) => {
                        const parts = value.split(":")
                        map[parseInt(parts[0], 10)] = parts[1] === "1"
                    })
                    return map
                }
                return undefined
            },
        },
    }

    constructor(
        protected key: string,
        protected defaultValue: T | undefined,
        protected type: SerializerType,
        protected config: ParamConfig,
    ) {}

    public getValue(): T | undefined {
        const deserializer = (this.serializers[this.type] as Serializer<T>).deserialize
        const queryValue = syncSingleton!.router.currentRoute.value.query[this.key]

        if (queryValue === "[undefined]") {
            return undefined
        }
        if (!deserializer) {
            console.error("No deserializer found for parameter", this.key)

            return undefined
        }
        const deserializedValue = deserializer(queryValue)
        if (deserializedValue === undefined) {
            return this.defaultValue
        }
        return deserializedValue
    }

    public setValue(value: T | undefined): void {
        const serializer = (this.serializers[this.type] as Serializer<T>).serialize

        if (value === this.defaultValue) {
            syncSingleton!.updateQuery(this.key, undefined)
            return
        }

        if (!serializer) {
            console.error("No serializer found for parameter", this.key)
            syncSingleton!.updateQuery(this.key, undefined)
            return
        }
        let serializedValue = value !== undefined ? serializer(value) : undefined
        if (serializedValue === undefined && !this.config.allowEmpty) {
            // If the value is undefined, but undefined is not a valid value, we return the default value
            syncSingleton!.updateQuery(this.key, undefined)
            return
        }
        if (serializedValue === undefined) {
            // If allowEmpty === true, we persist the undefined state in a way that we can recognize
            serializedValue = "[undefined]"
        }

        syncSingleton!.updateQuery(this.key, serializedValue)
    }
}

export class RouteQuerySynchronizer {
    protected globalQuery: LocationQuery
    protected queryParamsDirty = false
    public router: Router

    constructor(router: Router) {
        this.router = router
        // We get our own reference to the query here, because useRoute().query doesn't update immediately when it's changed (only in the next tick)
        this.globalQuery = { ...router.currentRoute.value.query }
        // Once the query changes, we use the changed values
        watch(
            () => router.currentRoute.value.query,
            () => {
                this.globalQuery = { ...router.currentRoute.value.query }
                this.queryParamsDirty = false
            },
        )
    }

    public updateQuery(key: string, value: string | undefined) {
        if (this.globalQuery[key] === value) {
            return
        }

        // @ts-ignore
        this.globalQuery = {
            ...this.globalQuery,
            [key]: value,
        }
        this.queryParamsDirty = true

        // We update the route in the next tick, otherwise we'd potentially do it multiple times
        nextTick(() => {
            if (this.queryParamsDirty) {
                this.updateRouteQueryParams()
            }
        })
    }

    protected updateRouteQueryParams() {
        this.router.replace({
            query: this.globalQuery,
        })

        this.queryParamsDirty = false
    }

    public useParam<T>(key: string, defaultValue: T, type: SerializerType, config?: ParamConfig): Ref<T | undefined> {
        const param = new RouteQueryParam(key, defaultValue, type, config || {})

        return computed<T | undefined>({
            get() {
                return param.getValue()
            },
            set(value) {
                param.setValue(value)
            },
        })
    }
}

let syncSingleton: RouteQuerySynchronizer | undefined

export const initRouteQuerySync = (router: Router) => {
    syncSingleton = new RouteQuerySynchronizer(router)
}

export const useRouteQueryParam = (key: string, defaultValue: any, type: SerializerType, config: ParamConfig = {}): Ref => {
    const ref = syncSingleton!.useParam(key, defaultValue, type, config)
    if (config.targetRef) {
        // If we have a target ref that this route param should be synced to, we set up the synchronization here
        syncRef(ref, config.targetRef, {
            deep: true,
        })
    }
    return ref
}
