import { toast } from "vue3-toastify"
import "vue3-toastify/dist/index.css"
import { defineStore } from "pinia"

type ToastType = "default" | "info" | "warning" | "success" | "error"
export const useToastStore = defineStore("toast-store", {
    state: () => ({}),
    actions: {
        show(message: string, type: ToastType) {
            toast(message, {
                theme: "auto",
                hideProgressBar: true,
                transition: "slide",
                autoClose: 3000,
                type,
            })
        },
        success(message: string) {
            this.show(message, "success")
        },
        error(message: string) {
            this.show(message, "error")
        },
        warning(message: string) {
            this.show(message, "warning")
        },
        info(message: string) {
            this.show(message, "info")
        },
    },
})
