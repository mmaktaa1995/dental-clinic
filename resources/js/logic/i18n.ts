import { useLocalStorage } from "@vueuse/core"
import { useI18n } from "vue-i18n"
import { ref } from "vue"

export const availableLanguages = {
    ar: "Arabic",
    en: "English",
    de: "German",
}

type KLI18n = ReturnType<typeof useI18n<any, keyof typeof availableLanguages>>
let i18n: KLI18n | null = null

const defaultLanguage = localStorage.getItem("language") || "ar"
const current = ref("ar")
const selectedLanguage = useLocalStorage<keyof typeof availableLanguages>("language", defaultLanguage, {
    listenToStorageChanges: false,
})

export async function setSelectedLanguage(language: keyof typeof availableLanguages = defaultLanguage): Promise<void> {
    selectedLanguage.value = language
    window.location.reload()
}

export function getSelectedLanguage() {
    return selectedLanguage
}

export function setHtmlLangAttributes(language: keyof typeof availableLanguages = defaultLanguage) {
    document.querySelector("html")?.setAttribute("lang", language)
    document.querySelector("html")?.setAttribute("dir", language === "ar" ? "rtl" : "ltr")
}

export function setI18n(newI18n: KLI18n) {
    i18n = newI18n
    current.value = selectedLanguage.value
}

export function getI18n() {
    if (!i18n) {
        console.error("i18n was not setup yet")
    }
    return i18n!
}
