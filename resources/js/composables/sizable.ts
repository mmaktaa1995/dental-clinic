import { computed } from "vue"

export function useSizeClass(size: string | number) {
    const sizeClass = computed(() => {
        const classes: Record<string | number, string> = {
            2: "h-2 w-2",
            3: "h-3 w-3",
            4: "h-4 w-4",
            5: "h-5 w-5",
            6: "h-6 w-6",
            8: "h-8 w-8",
            12: "h-12 w-12",
            16: "h-16 w-16",
            20: "h-20 w-20",
        }
        return classes[size] || "h-8 w-8" // Default to "h-8 w-8"
    })

    return sizeClass.value
}
