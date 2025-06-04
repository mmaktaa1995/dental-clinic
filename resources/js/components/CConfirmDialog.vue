<template>
  <div v-if="confirmStore.isOpen" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50" @click="confirmStore.handleCancel"></div>
    <div class="bg-white rounded-lg shadow-lg z-10 w-full max-w-md overflow-hidden">
      <div class="p-4 border-b" :class="headerClass">
        <h3 class="text-lg font-medium text-white">{{ confirmStore.options.title }}</h3>
      </div>
      <div class="p-4">
        <p class="text-gray-700">{{ confirmStore.options.message }}</p>
      </div>
      <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-end space-x-2">
        <button
          type="button"
          class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          @click="confirmStore.handleCancel"
        >
          {{ confirmStore.options.cancelText }}
        </button>
        <button
          type="button"
          :class="buttonClass"
          @click="confirmStore.handleConfirm"
        >
          {{ confirmStore.options.confirmText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useConfirmStore } from '@/modules/global/confirmStore';
import { computed } from 'vue';

const confirmStore = useConfirmStore();

const headerClass = computed(() => {
  switch (confirmStore.options.type) {
    case 'success':
      return 'bg-green-600';
    case 'warning':
      return 'bg-yellow-600';
    case 'danger':
      return 'bg-red-600';
    default:
      return 'bg-blue-600';
  }
});

const buttonClass = computed(() => {
  const baseClass = 'inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2';
  
  switch (confirmStore.options.type) {
    case 'success':
      return `${baseClass} bg-green-600 hover:bg-green-700 focus:ring-green-500`;
    case 'warning':
      return `${baseClass} bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500`;
    case 'danger':
      return `${baseClass} bg-red-600 hover:bg-red-700 focus:ring-red-500`;
    default:
      return `${baseClass} bg-blue-600 hover:bg-blue-700 focus:ring-blue-500`;
  }
});
</script>
