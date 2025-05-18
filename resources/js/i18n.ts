import { App } from "vue"
import { createI18n, Translation } from "vue-i18n"
import { getSelectedLanguage } from "@/logic/i18n"
import ar from "./lang/ar.json"
import en from "./lang/en.json"
import de from "./lang/de.json"
import { ar as dateFnsAr, de as dateFnsDe, enGB as dateFnsEn, fr as dateFnsFr } from "date-fns/locale"
import { setDefaultOptions } from "date-fns"

export const setupI18n = (app: App<Element>) => {
    app.component("Translation", Translation)

    const datetimeFormats = {
        de: {
            date: {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
            },
            datetime: {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "numeric",
                minute: "numeric",
            },
        },
        ar: {
            date: {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
            },
            datetime: {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "numeric",
                minute: "numeric",
            },
        },
        en: {
            date: {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
            },
            datetime: {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "numeric",
                minute: "numeric",
            },
        },
    }
    const i18n = createI18n({
        legacy: false,
        globalInjection: true,
        locale: getSelectedLanguage().value,
        fallbackLocale: "ar",
        warnHtmlMessage: false,
        warnHtmlInMessage: "off",
        messages: {
            ar,
            en,
            de,
        },
        datetimeFormats,
    })
    app.use(i18n)

    let dateFnsLocale = dateFnsEn
    switch (getSelectedLanguage().value) {
        case "de":
            dateFnsLocale = dateFnsDe
            break
        case "en":
            dateFnsLocale = dateFnsEn
            break
        case "ar":
            dateFnsLocale = dateFnsAr
            break
    }

    setDefaultOptions({
        locale: dateFnsLocale,
    })
}
