// Inspired by https://github.com/DanHulton/vue-deepunref
// Published on the MIT license
import { MaybeRef } from "@vueuse/core"
import { unref, isRef } from "vue"

const isObject = (val: unknown) => val !== null && typeof val === "object"
const isArray = Array.isArray

/**
 * Deeply unref a value, recursing into objects and arrays.
 *
 * @param {Mixed} val - The value to deeply unref.
 *
 * @return {Mixed}
 */
export const deepUnref = <T>(val: MaybeRef<T>): T => {
    const checkedVal = isRef(val) ? unref(val) : val

    if (!isObject(checkedVal)) {
        return checkedVal
    }

    if (isArray(checkedVal)) {
        return unrefArray(checkedVal)
    }

    // @ts-ignore-next-line
    return unrefObject(checkedVal as Record<string | number | symbol, unknown>)
}

/**
 * Unref a value, recursing into it if it's an object.
 *
 * @param {Mixed} val - The value to unref.
 *
 * @return {Mixed}
 */
const smartUnref = (val: unknown) => {
    // Non-ref object?  Go deeper!
    if (val !== null && !isRef(val) && typeof val === "object") {
        return deepUnref(val)
    }

    return unref(val)
}

/**
 * Unref an array, recursively.
 *
 * @param {Array} arr - The array to unref.
 *
 * @return {Array}
 */
// @ts-ignore-next-line
const unrefArray = <T extends any[]>(arr: T): T => arr.map(smartUnref)

/**
 * Unref an object, recursively.
 *
 * @param {Object} obj - The object to unref.
 *
 * @return {Object}
 */
const unrefObject = (obj: Record<string | number | symbol, unknown>) => {
    const unreffed: Record<keyof typeof obj, unknown> = {}

    // Object? un-ref it!
    Object.keys(obj).forEach((key) => {
        unreffed[key] = smartUnref(obj[key])
    })

    return unreffed
}
