<template>
    <label class="flex items-start cursor-pointer relative" :for="`checkbox-${randomId}`" @click="checkboxClicked">
        <input :id="`checkbox-${randomId}`" ref="checkbox" v-model="modelValue" :checked="modelValue || indeterminate" type="checkbox" class="checkbox peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300" :class="[borderColor]" />
        <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 right-[3px] transform ltr:left-[3px] -translate-y-1/2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </span>
    </label>
    <label v-if="label" class="cursor-pointer ml-2 text-slate-600 text-sm" :for="`checkbox-${randomId}`" @click="checkboxClicked">
        <div>
            <p class="font-medium">{{ label }}</p>
            <p v-if="hint" class="text-slate-500">{{ hint }}</p>
        </div>
    </label>
</template>

<script setup lang="ts">
import { computed, ref, watch, onMounted } from "vue"

const props = withDefaults(
    defineProps<{
        indeterminate?: boolean
        label?: string
        hint?: string
    }>(),
    {
        indeterminate: false,
        label: undefined,
        hint: undefined,
    },
)

const randomId = computed(() => {
    return new Date().valueOf() * Math.floor(Math.random() * 100)
})

const modelValue = defineModel<boolean | null | undefined>({ required: true })

const checkbox = ref<HTMLInputElement | null>(null)

const borderColor = computed(() => {
    return "checked:bg-sky-700 checked:border-cyan-700"
})

// Watch the `indeterminate` prop and update the checkbox state
watch(
    () => props.indeterminate,
    (indeterminateVal) => {
        if (checkbox.value) {
            checkbox.value.indeterminate = indeterminateVal
        }
    },
    { immediate: true },
)

// Prevent label click propagation
const checkboxClicked = (event: any) => {
    event.stopPropagation()
}

// Ensure the checkbox respects the `indeterminate` state on mount
onMounted(() => {
    if (checkbox.value) {
        checkbox.value.indeterminate = props.indeterminate
    }
})
</script>

<style scoped>
.checkbox[type="checkbox"]:indeterminate {
    @apply bg-sky-700 border-cyan-700;
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3E%3C/svg%3E");
}
</style>
