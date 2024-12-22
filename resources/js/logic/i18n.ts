import { useLocalStorage } from "@vueuse/core"
import { useI18n } from "vue-i18n"
import { ref } from "vue"

export const availableLanguages = {
    ar: "Arabic",
    en: "English (beta)",
}
type KLI18n = ReturnType<typeof useI18n<any, keyof typeof availableLanguages>>
let i18n: KLI18n | null = null

const defaultLanguage = "ar"
const current = ref("ar")
const selectedLanguage = useLocalStorage<keyof typeof availableLanguages>("selectedLanguage", defaultLanguage, {
    listenToStorageChanges: false,
})

export async function setSelectedLanguage(language: keyof typeof availableLanguages): Promise<void> {
    selectedLanguage.value = language
    // const accountStore = useAccountStore()
    // if (accountStore.isLoggedIn) {
    //     await api.post("/account/set-language", {
    //         language,
    //     })
    //     window.location.reload()
    // }
}

export function getSelectedLanguage() {
    return selectedLanguage
}

export function setI18n(newI18n: KLI18n) {
    i18n = newI18n
    current.value = selectedLanguage.value
}

export function getI18n() {
    if (!i18n) {
        console.error("i18n was not setup yet")
    }
    console.log(i18n)
    return i18n!
}
