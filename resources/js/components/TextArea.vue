<template>
    <div
        class="relative w-full"
        :class="{
            'opacity-50': disabled,
        }"
    >
        <label
            :for="name"
            class="absolute z-10 right-3 ltr:left-3 ltr:right-auto text-sm transition-all duration-150 text-gray-600 pointer-events-none"
            :class="{
                'top-[-10px] text-xs bg-white px-1.5 font-medium': isFocused || modelValue || labelFloated,
                'top-[12px] ': !isFocused && !modelValue && !labelFloated,
            }"
        >
            {{ label }}
        </label>
        <textarea
            v-bind="attrs"
            :id="name"
            v-model="modelValue"
            autocomplete="off"
            :rows="rows"
            :disabled="disabled"
            class="block border border-gray-300 text-gray-600 outline-none focus:border-teal-500 focus:ring-teal-500 px-2 py-3 rounded-md sm:text-sm w-full text-right ltr:text-left disabled:cursor-not-allowed"
            @focus="(isFocused = true)"
            @blur="(isFocused = false)"
        >
        </textarea>
        <small v-if="errors && errors[name]" class="text-pink-600 text-xs text-right block">
            {{ errors[name][0] }}
        </small>
    </div>
</template>

<script setup lang="ts">
import { ref, useAttrs } from "vue"

// Props
const modelValue = defineModel<string | number | null>({ required: true })

withDefaults(
    defineProps<{
        name: string
        label?: string
        rows?: string
        disabled?: boolean
        labelFloated?: boolean
        errors?: Record<string, any>
    }>(),
    {
        label: "",
        rows: "5",
        disabled: false,
        labelFloated: false,
        errors: Object.assign({}),
    },
)

// State for managing focus
const isFocused = ref(false)
const attrs = useAttrs()
</script>
