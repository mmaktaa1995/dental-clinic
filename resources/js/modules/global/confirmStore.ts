import { defineStore } from 'pinia';
import { ref } from 'vue';

interface ConfirmOptions {
  title: string;
  message: string;
  confirmText?: string;
  cancelText?: string;
  type?: 'info' | 'success' | 'warning' | 'danger';
}

export const useConfirmStore = defineStore('confirm-store', () => {
  const isOpen = ref(false);
  const options = ref<ConfirmOptions>({
    title: '',
    message: '',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    type: 'info',
  });
  
  let resolvePromise: ((value: boolean) => void) | null = null;
  
  const confirm = (confirmOptions: ConfirmOptions): Promise<boolean> => {
    options.value = {
      ...options.value,
      ...confirmOptions,
    };
    isOpen.value = true;
    
    return new Promise((resolve) => {
      resolvePromise = resolve;
    });
  };
  
  const handleConfirm = () => {
    isOpen.value = false;
    if (resolvePromise) {
      resolvePromise(true);
      resolvePromise = null;
    }
  };
  
  const handleCancel = () => {
    isOpen.value = false;
    if (resolvePromise) {
      resolvePromise(false);
      resolvePromise = null;
    }
  };
  
  return {
    isOpen,
    options,
    confirm,
    handleConfirm,
    handleCancel,
  };
});
