import { parse, parseISO } from "date-fns"
import { computed } from "vue"
import { RouteLocationNormalizedLoaded, RouteRecord } from "vue-router"

/**
 * https://stackoverflow.com/a/16608074/2660393
 * @param object
 */
export function isLiteralObjectOrArray(object: any): boolean {
    if (Array.isArray(object)) {
        return true
    }
    return !!object && object.constructor === Object
}

/**
 * Converts our internal language representation to the ISO 639-1 format
 * https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
 */
export function languageToISO639(language: string): string {
    const mappings: Record<string, string> = {
        de_formal: "de",
        jp: "ja",
        al: "sq",
    }
    if (mappings[language] !== undefined) {
        return mappings[language]
    }
    return language
}

/**
 * Typesafe way to check if a value is not null || undefined
 */
export function notEmpty<TValue>(value: TValue | null | undefined): value is TValue {
    return !(value === null || value === undefined)
}

export const getContrastColor = (bgColor: string | null) => {
    if (!bgColor) {
        return "#000000"
    }
    const color = bgColor.charAt(0) === "#" ? bgColor.substring(1, 7) : bgColor
    const r = parseInt(color.substring(0, 2), 16) // hexToR
    const g = parseInt(color.substring(2, 4), 16) // hexToG
    const b = parseInt(color.substring(4, 6), 16) // hexToB
    // it's a Weighted W3C Formula
    return r * 0.299 + g * 0.587 + b * 0.114 > 186 ? "#000000" : "#fff"
}

export const parseDate = (date: string): Date => {
    if (date.match(/^\d{4}-\d{2}-\d{2}$/)) {
        return parse(date, "yyyy-MM-dd", new Date())
    }
    return parseISO(date)
}

export const getMetaModule = (route: RouteLocationNormalizedLoaded) => {
    return computed(() => {
        return route.matched.find((matched: RouteRecord) => matched.meta?.module)?.meta?.module ?? "media-library"
    })
}

export async function getAspectRatioFromUrl(url: string | null | undefined): Promise<number | null> {
    if (!url) {
        return null
    }
    return new Promise((resolve, reject) => {
        const img = new Image()
        img.onload = () => {
            const width = img.naturalWidth
            const height = img.naturalHeight
            const aspectRatio = width / height
            resolve(aspectRatio)
        }
        img.onerror = reject
        img.src = url
    })
}

/**
 * Converts a base64 string to a File object
 * @param {string} base64String - The base64 string to convert
 * @param {string} filename - The desired filename
 * @param {string} mimeType - The MIME type of the file (e.g., 'image/jpeg', 'application/pdf')
 * @returns {File} A File object created from the base64 data
 */
export function base64ToFile(base64String: string, filename: string, mimeType: string) {
    // Remove data URL prefix if present
    const base64WithoutPrefix = base64String.replace(/^data:.*,/, "")

    // Convert base64 to binary
    const binaryString = window.atob(base64WithoutPrefix)

    // Create an array buffer from the binary string
    const bytes = new Uint8Array(binaryString.length)
    for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i)
    }

    // Create a Blob from the array buffer
    const blob = new Blob([bytes], { type: mimeType })

    // Create and return a File object
    return new File([blob], filename, { type: mimeType })
}

/**
 * Converts a string to URL-friendly slug, handling special characters like äöü.
 */
export function slugify(str: string): string {
    const charMap: Record<string, string> = {
        à: "a",
        á: "a",
        â: "a",
        ã: "a",
        ä: "ae",
        å: "a",
        æ: "ae",
        ç: "c",
        è: "e",
        é: "e",
        ê: "e",
        ë: "e",
        ì: "i",
        í: "i",
        î: "i",
        ï: "i",
        ñ: "n",
        ò: "o",
        ó: "o",
        ô: "o",
        õ: "o",
        ö: "oe",
        ø: "o",
        ß: "ss",
        ù: "u",
        ú: "u",
        û: "u",
        ü: "ue",
        ý: "y",
        ÿ: "y",
        ā: "a",
        ē: "e",
        ī: "i",
        ō: "o",
        ū: "u",
    }

    return str
        .toLowerCase()
        .split("")
        .map((char) => charMap[char] || char)
        .join("")
        .replace(/[^\w\s-]/g, "")
        .replace(/[\s_]+/g, "-")
        .replace(/^-+|-+$/g, "")
        .replace(/-{2,}/g, "-")
}
