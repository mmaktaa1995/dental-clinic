import { Ref, ref, UnwrapRef, watch } from "vue"
import { Store } from "pinia"

export type AttributeChangedReturn = {
    isChanged: Ref<boolean>
    resetStore: () => void
    getOriginalValue: () => any
}

export type UnRefAttributeChangedReturn = UnwrapRef<AttributeChangedReturn>

export type WatcherSettings = {
    isObject?: boolean // Indicates that the entity to be watched is an object
    exclude?: string[] // Specify keys that should not be watched
}

const defaultSettings: Required<WatcherSettings> = {
    isObject: true,
    exclude: [],
}

export function useCheckStoreAttributeChanged<S extends Store>(store: S, attributeToCheck: string, userSettings: WatcherSettings = {}): AttributeChangedReturn {
    const isChanged = ref(false)
    let hasInitialDataSet = false
    const settings: Required<WatcherSettings> = {
        ...defaultSettings,
        ...userSettings,
    }

    let attributeValue: any = settings.isObject ? {} : undefined

    watch(store, (state: S) => {
        // Set default value if the type of required
        // attribute is object or normal value
        const newValue = resolveObjectValue(state, attributeToCheck, settings.isObject ? {} : null)
        if (settings.isObject) {
            // check if initial value is empty and
            // the state has a value for it
            if (!hasInitialDataSet && isEmpty(attributeValue) && !isEmpty(newValue)) {
                attributeValue = JSON.parse(JSON.stringify(newValue))
                hasInitialDataSet = true
            }
            if (!isEmpty(attributeValue) && !isEmpty(newValue)) {
                // look at each attribute of this object
                // to check if it's changed
                isChanged.value = !deepEqual(attributeValue, newValue, settings)
            }
        } else {
            if (!hasInitialDataSet && typeof attributeValue === "undefined" && typeof newValue !== "undefined") {
                if (Array.isArray(newValue)) {
                    attributeValue = JSON.parse(JSON.stringify(newValue))
                } else {
                    attributeValue = newValue
                }
                hasInitialDataSet = true
            }
            isChanged.value = !deepEqual(attributeValue, newValue, settings)
        }
    })

    return {
        isChanged,
        resetStore() {
            isChanged.value = false
            const newVal = resolveObjectValue(store, attributeToCheck, settings.isObject ? {} : null)
            attributeValue = settings.isObject || Array.isArray(newVal) ? JSON.parse(JSON.stringify(newVal)) : newVal
            hasInitialDataSet = true
        },
        getOriginalValue(): any {
            return attributeValue
        },
    }
}

const isEmpty = (obj: null | NonNullable<unknown>) => {
    if (obj === null) {
        return true
    }
    return !Object.entries(obj).length
}

const resolveObjectValue = (object: NonNullable<any>, path: string, defaultValue: any = null) => {
    return path.split(".").reduce((object, pathToValue) => (object ? object[pathToValue] : defaultValue), object)
}

function deepEqual(oldObject: any, newObject: any, settings: Required<WatcherSettings>): boolean {
    return oldObject && newObject && typeof oldObject === "object" && typeof newObject === "object"
        ? Object.keys(oldObject).length === Object.keys(newObject).length &&
              Object.keys(oldObject)
                  .filter((key) => ![...settings.exclude, "created_at", "updated_at"].includes(key))
                  .reduce(function (isEqual, key) {
                      return isEqual && deepEqual(oldObject[key], newObject[key], settings)
                  }, true)
        : oldObject === newObject
}
