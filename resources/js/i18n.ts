import { App } from "vue"
import { createI18n, Translation } from "vue-i18n"
import { getSelectedLanguage } from "@/logic/i18n"
// @ts-ignore
import ar from "./lang/ar.json"
// @ts-ignore
import en from "./lang/en.json"
import { ar as dateFnsAr, enGB as dateFnsEn } from "date-fns/locale"
import { setDefaultOptions } from "date-fns"

export const setupI18n = (app: App<Element>) => {
    app.component("Translation", Translation)

    const datetimeFormats = {
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
        locale: getSelectedLanguage().value,
        fallbackLocale: "ar",
        warnHtmlMessage: false,
        warnHtmlInMessage: "off",
        messages: {
            ar,
            en,
        },
        // @ts-expect-error seems to be a bug in the ts definition
        datetimeFormats,
    })
    app.use(i18n)

    setDefaultOptions({
        locale: getSelectedLanguage().value === "ar" ? dateFnsAr : dateFnsEn,
    })
}
