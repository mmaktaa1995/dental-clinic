<template>
    <div v-if="opened" :class="`fixed z-10 inset-0 overflow-y-auto `" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div :class="`inline-block w-full align-bottom rounded-lg text-right overflow-hidden transform transition-all sm:my-8 sm:align-middle mx-auto sm:w-full duration-200 shadow  ${opened ? 'scale-100' : 'scale-0'}`">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { ButtonType } from "@/components/CButton.vue"

const opened = defineModel<boolean>({ required: true })

const back = () => {
    opened.value = false
}
</script>
