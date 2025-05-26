<template>
  <div
    v-if="opened"
    :class="`fixed z-50 inset-0 overflow-y-auto `"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
  >
    <div
      class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
      <div
        :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`"
        aria-hidden="true"
        @click="back"
      ></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"
        >&#8203;</span
      >
      <div
        :class="`inline-block w-full align-bottom bg-white rounded-lg text-right ltr:text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200 shadow  ${opened ? 'scale-100' : 'scale-0'}`"
      >
        <h3
          id="modal-title"
          class="text-lg bg-gray-100 px-6 py-4 leading-6 font-medium text-gray-700 border-b"
        >
          <slot name="header"></slot>
        </h3>
        <div class="bg-white px-6 pt-6 pb-6 sm:pb-6 shadow-sm">
          <div :class="{ 'sm:flex sm:items-start': showIcon }">
            <div
              v-if="showIcon && icon"
              class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10 bg-opacity-15"
              :class="[icon.bg]"
            >
              <c-icon>{{ icon.icon }}</c-icon>
            </div>
            <div class="text-right ltr:text-left text-gray-500">
              <slot name="body"></slot>
            </div>
          </div>
        </div>
        <div
          class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row gap-2 justify-start ltr:justify-end border-t"
        >
          <template v-if="$slots['actions']">
            <slot name="actions"></slot>
          </template>
          <template v-else>
            <CAsyncButton :type="buttonType" :loading="loading" @click="confirm">{{
              $t(confirmLabel)
            }}</CAsyncButton>
            <CButton type="default" @click="back">
              {{ $t('global.actions.cancel') }}
            </CButton>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { ButtonType } from '@/components/CButton.vue';

const opened = defineModel<boolean>({ required: true });
const loading = defineModel<boolean>('loading', { required: false });

const props = withDefaults(
  defineProps<{
    confirmLabel?: string;
    type?: 'info' | 'warning' | 'error';
    showIcon?: boolean;
  }>(),
  {
    confirmLabel: 'global.actions.confirm',
    type: undefined,
    showIcon: undefined,
  }
);

const $emits = defineEmits(['confirmCallback']);

const buttonType = computed<ButtonType>(() => {
  if (!props.type) {
    return 'primary';
  }
  return props.type;
});

const icon = computed(() => {
  if (!props.showIcon) {
    return '';
  }
  if (!props.type) {
    return { icon: 'far fa-exclamation text-gray-300', bg: 'bg-gray-400' };
  }
  const iconTypes = {
    error: { icon: 'fas fa-warning text-red-500', bg: 'bg-red-500' },
    info: { icon: 'fas fa-info-circle text-blue-300', bg: 'bg-blue-600' },
    warning: {
      icon: 'fas fa-warning text-orange-300',
      bg: 'bg-orange-500',
    },
  };
  return iconTypes[props.type];
});

const back = () => {
  opened.value = false;
};
const confirm = () => {
  $emits('confirmCallback');
};
</script>
