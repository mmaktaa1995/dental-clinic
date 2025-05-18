import { defineStore } from "pinia"

export const useLayoutStore = defineStore("layout-store", {
    state: () => ({
        showSidebar: true,
        showNavBar: true,
    }),
    actions: {
        toggleSideBar() {
            this.showSidebar = !this.showSidebar
        },
        toggleNavBar() {
            this.showNavBar = !this.showNavBar
        },
    },
})
