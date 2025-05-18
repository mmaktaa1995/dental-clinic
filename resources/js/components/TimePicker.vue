<template>
    <CTextField v-model="modelValue" :label-floated="true" :name type="time" :label="label" :disabled="disabled" :errors="errors"></CTextField>
</template>

<script setup lang="ts">
// Props
withDefaults(
    defineProps<{
        name: string
        label?: string
        disabled?: boolean
        errors?: Record<string, any>
    }>(),
    {
        label: "",
        disabled: false,
        errors: Object.assign({}),
    },
)

// Define model
const modelValue = defineModel<string | null>({ required: true })

// Emit events
defineEmits(["change"])
</script>
