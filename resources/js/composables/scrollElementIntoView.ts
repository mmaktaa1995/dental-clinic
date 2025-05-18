import { MaybeRef, useScroll } from "@vueuse/core"
import { nextTick } from "vue"

export type ScrollElementIntoView = {
    scrollToTop: () => void
}
export function useScrollElementIntoView(detailPageContentElement: MaybeRef<HTMLElement> | null, specificElement: HTMLElement | null): ScrollElementIntoView {
    if (!detailPageContentElement) {
        return {
            scrollToTop: () => {
                throw new Error("Element is not defined!")
            },
        }
    }
    const { y } = useScroll(detailPageContentElement, { behavior: "smooth" })
    function scrollToTop() {
        nextTick().then(() => {
            if (specificElement) {
                specificElement.scrollIntoView({
                    behavior: "smooth",
                    inline: "nearest",
                })
            } else {
                y.value = 0
            }
        })
    }
    return {
        scrollToTop,
    }
}
