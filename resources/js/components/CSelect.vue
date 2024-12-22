<template>
    <div v-bind="attrs" class="relative w-full">
        <label
            v-if="label"
            :for="`select-${name}`"
            class="absolute z-10 right-3 ltr:left-3 text-sm transition-all duration-150 text-gray-600 pointer-events-none"
            :class="{
                'top-[-10px] text-xs bg-white px-1.5 font-medium': isFocused || modelValue,
                'top-[12px] ': !isFocused && !modelValue,
            }"
        >
            {{ label }}
        </label>
        <select
            :id="`select-${name}`"
            v-model="modelValue"
            :name="name"
            :disabled="disabled"
            class="py-3 px-4 pe-5 block w-full border border-gray-200 outline-none rounded-md text-sm disabled:opacity-50 disabled:pointer-events-none text-right ltr:text-left focus:border-teal-500 focus:ring-teal-500 bg-transparent disabled:cursor-not-allowed"
            @focus="(isFocused = true)"
            @blur="(isFocused = false)"
            @change="$emit('change')"
        >
            <option v-if="hint" selected disabled value="">{{ hint }}</option>
            <option v-for="option in options" :key="option.id" :value="option.id">
                {{ option.label }}
            </option>
        </select>
        <small v-if="errors && errors[name]" class="text-red-600 text-xs text-right block">
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
