<template>
    <div class="relative w-full">
        <label
            v-if="label"
            :for="`select-${name}`"
            class="absolute z-10 right-3 ltr:left-3 ltr:right-auto text-sm transition-all duration-150 text-gray-600 pointer-events-none"
            :class="{
                'top-[-10px] text-xs bg-white px-1.5 font-medium': isFocused || modelValue || hint,
                'top-[12px] ': !isFocused && !modelValue && !hint,
            }"
        >
            {{ label }}
        </label>
        <select
            v-bind="attrs"
            :id="`select-${name}`"
            v-model="modelValue"
            :name="name"
            :disabled="disabled"
            class="c-select appearance-none bg-white py-3 px-4 pe-8 block w-full border border-gray-200 outline-none rounded-md text-sm disabled:opacity-50 disabled:pointer-events-none text-right ltr:text-left focus:border-teal-500 focus:ring-teal-500 bg-transparent disabled:cursor-not-allowed"
            @focus="(isFocused = true)"
            @blur="(isFocused = false)"
            @change="$emit('change')"
        >
            <option v-if="hint" selected>{{ hint }}</option>
            <option v-for="option in options" :key="option.id" :value="option.id">
                {{ option.label }}
            </option>
        </select>
        <small v-if="errors && errors[name]" class="text-rose-600 text-xs text-right block">
            {{ errors[name][0] }}
        </small>
    </div>
</template>

<script setup lang="ts">
import { ref, useAttrs } from "vue"

// Define the model
const modelValue = defineModel<string | number | null>({ required: true })

// Props
withDefaults(
    defineProps<{
        disabled?: boolean
        name: string
        label?: string
        hint?: string
        options: { id: any; label: any }[]
        errors?: Record<string, any>
    }>(),
    {
        disabled: false,
        label: "",
        hint: "",
        errors: Object.assign({}),
    },
)

defineEmits(["change"])

// Track focus state
const isFocused = ref(false)
const attrs = useAttrs()
</script>

<style scoped>
.c-select {
    background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"%3E%3Cpath stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"%3E%3C/path%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: left 0.5rem center;
    background-size: 1rem 1rem;
}
[dir="ltr"] .c-select {
    background-position: right 0.5rem center;
}
</style>
