<template>
    <tbody>
        <tr>
            <td :colspan="columnCount">
                <div class="relative h-1 bg-gray-200 overflow-hidden">
                    <div class="absolute left-0 top-0 h-full bg-gray-400 transition-transform duration-400 ease-in-out animate-indeterminate"></div>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script setup lang="ts">
// Props for customizing the loader
withDefaults(
    defineProps<{
        columnCount: number
    }>(),
    {},
)
</script>

<style scoped>
/* Animation for the indeterminate progress bar */
@keyframes indeterminate {
    0% {
        transform: translateX(-100%);
    }
    50% {
        transform: translateX(0%);
    }
    100% {
        transform: translateX(100%);
    }
}

.animate-indeterminate {
    animation: indeterminate 1s infinite linear;
    width: 50%; /* Width of the moving bar */
}
</style>
