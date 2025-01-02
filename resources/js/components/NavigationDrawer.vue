<template>
    <div v-if="isOpen" class="left-0 ltr:right-0 ltr:left-auto inset-y-0">
        <!-- Overlay (only visible when drawer is open) -->
        <div class="fixed inset-0 bg-gray-700 bg-opacity-30 transition-opacity" :class="{ hidden: !isOpen }" @click="toggleDrawer"></div>

        <!-- Navigation Drawer -->
        <div class="z-50 bg-white shadow-md transform transition-transform h-full" :class="{ '-translate-x-full': !isOpen, 'translate-x-0': isOpen }">
            <slot></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
const isOpen = defineModel<boolean>({ required: true })

const toggleDrawer = () => {
    isOpen.value = !isOpen.value
}
</script>
