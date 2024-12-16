<template>
    <div v-bind="attrs">
        <label :for="name" class="block text-sm font-medium text-gray-700 text-right">{{ label }}</label>
        <input :id="name" v-model="modeValue" type="text" class="block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full" />
        <small v-if="errors && errors[name]" class="text-red-600 text-xs text-right block">{{ errors.name[0] }}</small>
    </div>
</template>

<script setup lang="ts">
import { useAttrs } from "vue"

const modeValue = defineModel<string>({ required: true })
// Props
defineProps({
    type: {
        type: String,
        default: "text",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    errors: {
        type: Object,
        required: false,
        default: Object.assign({}),
    },
})

// Access $attrs and $listeners
const attrs = useAttrs()
</script>
