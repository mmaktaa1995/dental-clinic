// import { useSnackbarsStore } from "@/store/snackbars"
import { Router } from "vue-router"

import { deepUnref } from "@/logic/deepUnref"
// import { useAccountStore } from "@/modules/account/store"
import { getI18n } from "@/logic/i18n"
import { useAccountStore } from "@/modules/auth/accountStore"

export const REQUEST_ABORTED = "aborted"

export const api = {
    lastErrorShown: null as null | number,
    lastConnectionErrorShown: null as null | number,
    router: null as unknown as Router,
    isUnloading: false as boolean,
    setup(router: Router) {
        this.router = router
        window.addEventListener("beforeunload", () => {
            this.isUnloading = true
        })
    },
    send(url: string, method: "POST" | "GET" | "DELETE" | "PUT", params?: Record<string, any>, body?: Record<string, any> | FormData, abortController: AbortController | null = null): Promise<any> {
        const headers = {
            ...this.getDefaultHeaders(),
            // ...this.getAppIdHeader(),
        }

        return new Promise((resolve, reject) => {
            const requestInit: RequestInit = {
                headers,
                credentials: "include",
                method: method || "POST",
            }
            if (abortController) {
                requestInit.signal = abortController.signal
            }
            if (body instanceof FormData) {
                requestInit.body = body
                if ("Content-Type" in requestInit.headers!) {
                    delete requestInit.headers["Content-Type"]
                }
            } else if (body) {
                requestInit.body = JSON.stringify(deepUnref(body))
            }
            fetch(api.getURL(url, params), requestInit).then(
                (response) => {
                    if (!response.ok) {
                        const accountStore = useAccountStore()
                        if (response.headers.get("x-logout-user") === "1") {
                            if (accountStore.isLoggedIn) {
                                accountStore.logout().then(() => {
                                    this.router.replace({
                                        name: "login",
                                    })
                                })
                            }
                        } else if (response.headers.get("x-privacy-note-confirmation-needed") === "1") {
                            if (accountStore.isLoggedIn) {
                                this.router.replace({
                                    name: "privacy-note",
                                })
                            }
                        } else {
                            this.getErrorMessage(response).then(reject)
                        }
                        return
                    }

                    switch (response.headers.get("content-type")) {
                        case "application/json":
                            return response.json().then(resolve).catch(reject)
                        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                            response.blob().then((blob) => {
                                const filename = this.getFilenameFromHeaders(response.headers)
                                const url = window.URL.createObjectURL(blob)
                                const a = document.createElement("a")
                                a.href = url
                                if (filename) {
                                    a.download = filename
                                }
                                document.body.appendChild(a) // we need to append the element to the dom -> otherwise it will not work in firefox
                                a.click()
                                a.remove()
                                resolve(true)
                            })
                            break
                        default:
                            resolve(response)
                    }
                },
                (error) => {
                    if (abortController && abortController.signal.aborted) {
                        reject(REQUEST_ABORTED)
                        return
                    }
                    this.handleConnectionError()
                    reject(error)
                },
            )
        })
    },
    get(url: string, params?: Record<string, any>): Promise<any> {
        return this.send(url, "GET", params)
    },
    post(url: string, body?: Record<string, any> | FormData, abortController: AbortController | null = null) {
        return this.send(url, "POST", undefined, body, abortController)
    },
    delete(url: string, body?: Record<string, any>) {
        return this.send(url, "DELETE", undefined, body)
    },
    getErrorMessage(response: Response) {
        try {
            return response.json().then((res) => {
                if (res.error) {
                    return res.error
                }
                if (res.message) {
                    if (this.isBinnedException(res.message)) {
                        const snackbarsStore = useSnackbarsStore()
                        // @ts-ignore
                        const { t } = getI18n()
                        snackbarsStore.error(t(res.message?.error))
                    }
                    return res.message
                }
                return "Keine Antwort vom Server erhalten. Bitte versuchen Sie es später erneut."
            })
        } catch (e) {
            return Promise.resolve("Ein unbekannter Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.")
        }
    },
    getFilenameFromHeaders(headers: Response["headers"]) {
        // The header looks something like this:
        // attachment; filename="test.xlsx"
        const contentDisposition = headers.get("content-disposition")
        if (!contentDisposition) {
            return null
        }
        const parts = contentDisposition.split(";").map((part) => part.trim().split("="))
        const filenamePart = parts.find((part) => part[0] === "filename")
        if (!filenamePart) {
            return null
        }
        return filenamePart[1].replaceAll('"', "")
    },
    handleConnectionError() {
        if (window.navigator.onLine) {
            // If the last error was shown less than 3 seconds ago, we don't show another one
            const currentDate = Date.now()
            if (this.lastConnectionErrorShown && currentDate - this.lastConnectionErrorShown < 3000) {
                return false
            }
            this.lastConnectionErrorShown = currentDate
            const snackbarsStore = useSnackbarsStore()
            snackbarsStore.error("Es ist ein Verbindungsfehler aufgetreten. Bitte versuchen Sie es später erneut.")
        }
        return true
    },
    getURL(path: string, params: null | Record<string, any> = null) {
        let parsedParams = ""
        if (params !== null && Object.keys(params).length > 0) {
            parsedParams += "?"
            parsedParams += Object.keys(params)
                .map((k) => `${encodeURIComponent(k)}=${encodeURIComponent(params[k])}`)
                .join("&")
        }
        let domain = import.meta.env.VITE_BACKEND_ROOT
        if (path.indexOf("http://") === 0 || path.indexOf("https://") === 0) {
            domain = ""
        }
        if (typeof domain === "undefined") {
            domain = ""
        }
        console.log(domain, path)
        return domain + "/api" + path.trimStart("/") + parsedParams
    },
    getDefaultHeaders(): Record<string, string> {
        // const appConfigStore = useAppConfigStore()
        const language = "ar"
        // if (appConfigStore.settings.defaultLanguage) {
        //     language = appConfigStore.settings.defaultLanguage
        // }
        const headers = {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-LANGUAGE": language,
            ...this.getAuthHeaders(),
        }
        return headers
    },
    getAuthHeaders() {
        const token = document.head.querySelector('meta[name="csrf-token"]')
        const access_token = localStorage.getItem("access_token")

        const headers = {}
        if (token) {
            headers["X-CSRF-TOKEN"] = token.content
        }

        if (access_token) {
            headers["Authorization"] = "Bearer " + access_token
        }
        return headers
    },
    // getAppIdHeader(): Record<string, string> {
    //     const appConfigStore = useAppConfigStore()
    //     if (!appConfigStore.appId) {
    //         return {}
    //     }
    //     return {
    //         "X-APP-ID": appConfigStore.appId.toString(),
    //     }
    // },
    isBinnedException(response: { message: string; exception: string }): boolean {
        return response.exception === "BinnedEntryException"
    },
}
