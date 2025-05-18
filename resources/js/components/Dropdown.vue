<template>
    <div ref="dropdown">
        <AsyncButton :type :disabled :loading class="w-36" @click="toggleDropdown">
            <span class="flex justify-between items-center">
                <span class="">{{ buttonLabel }}</span>
                <span class="">
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                    </svg>
                </span>
            </span>
        </AsyncButton>

        <!-- Dropdown menu -->
        <Transition name="fade">
            <div v-show="isOpen" class="relative">
                <div id="dropdown" class="absolute top-0 right-0 ltr:left-0 ltr:right-auto z-10 bg-white divide-y divide-gray-100 rounded-lg shadow min-w-32 max-w-44">
                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                        <li v-for="(label, id) in items" :key="id" class="block cursor-pointer transition px-4 py-2 hover:bg-gray-100" @click="itemSelected(id)">
                            {{ label }}
                        </li>
                    </ul>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from "vue"
import AsyncButton from "@/components/AsyncButton.vue"
import { ButtonType } from "@/components/CButton.vue"

withDefaults(
    defineProps<{
        items: Record<string | number, string>
        type?: ButtonType
        disabled?: boolean
        loading?: boolean
        buttonLabel: string
    }>(),
    {
        type: "primary",
        disabled: false,
        loading: false,
    },
)
const $emits = defineEmits(["select"])

const dropdown = ref<HTMLElement | null>(null)
const isOpen = ref(false)

const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

const itemSelected = (actionId: string | number) => {
    $emits("select", actionId)
    isOpen.value = false
}
const handleClickOutside = (event: MouseEvent) => {
    if (dropdown.value && !dropdown.value.contains(event.target as Node)) {
        isOpen.value = false
    }
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside)
})

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside)
})
</script>

<style scoped>
/* Fade Transition Styles */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
